require("dotenv").config();
const express = require("express");
const mysql = require("mysql2/promise");
const jwt = require("jsonwebtoken");
const cors = require("cors");
const { createWhirlpool } = require("hash-wasm");

const app = express();
app.use(cors());
app.use(express.json());

// DB pool
const pool = mysql.createPool({
  host: process.env.TEST_DB_HOST,
  user: process.env.TEST_DB_USER,
  password: process.env.TEST_DB_PASS,
  database: process.env.TEST_DB_NAME,
  waitForConnections: true,
  connectionLimit: 10,
});

// Utility: send standardized error
function sendError(res, code, msg) {
  return res.status(code).json({ success: false, msg });
}

// Utility: auth middleware
function requireAuth(req, res, next) {
  const hdr = req.headers.authorization || "";
  const token = hdr.split(" ")[1];
  if (!token) return sendError(res, 401, "No token provided");
  try {
    const payload = jwt.verify(token, process.env.JWT_SECRET);
    req.user = payload; // { uid, username, adminlevel }
    next();
  } catch {
    return sendError(res, 401, "Invalid or expired token");
  }
}

// Hash helper
async function whirlpoolHash(str) {
  const hasher = await createWhirlpool();
  hasher.init();
  hasher.update(str);
  return hasher.digest("hex").toUpperCase(); // SA-MP stores uppercase hashes
}

// LOGIN
app.post("/api/login", async (req, res) => {
  const { username, password } = req.body;
  try {
    const [rows] = await pool.query(
      "SELECT uid, username, password, adminlevel, bank, cash FROM users WHERE username = ? LIMIT 1",
      [username]
    );

    if (rows.length === 0) return sendError(res, 401, "User not found");

    const user = rows[0];

    // Hash incoming password with Whirlpool
    const hashed = await whirlpoolHash(password);

    if (hashed !== user.password) {
      return sendError(res, 401, "Invalid password");
    }

    // Create token with adminlevel
    const token = jwt.sign(
      { uid: user.uid, username: user.username, adminlevel: user.adminlevel },
      process.env.JWT_SECRET,
      { expiresIn: "1d" }
    );

    delete user.password; // never send hash
    res.json({ success: true, token, user });
  } catch (err) {
    console.error(err);
    sendError(res, 500, "Server error");
  }
});

// DASHBOARD
app.get("/api/dashboard", requireAuth, async (req, res) => {
  try {
    const uid = req.user.uid;

    const [[user]] = await pool.query(
      "SELECT uid, username, bank, cash, adminlevel FROM users WHERE uid = ? LIMIT 1",
      [uid]
    );
    if (!user) return sendError(res, 404, "User not found");

    const [[{ total: cardTotal }]] = await pool.query(
      "SELECT COALESCE(SUM(balance),0) AS total FROM cards WHERE owner = ?",
      [uid]
    );

    const wealth = Number(user.bank) + Number(user.cash) + Number(cardTotal);

    const [gangs] = await pool.query("SELECT * FROM gangs");
    const [gangranks] = await pool.query("SELECT * FROM gangranks");

    const [announcements] = await pool.query(
      "SELECT id, message, image_url, created_at FROM announcements ORDER BY created_at DESC"
    );
    const [updates] = await pool.query(
      "SELECT id, updates, image_url, created_at FROM updates ORDER BY created_at DESC"
    );

    res.json({
      success: true,
      data: { user, gangs, gangranks, cardTotal, wealth, announcements, updates },
    });
  } catch (err) {
    console.error(err);
    sendError(res, 500, "Server error");
  }
});

// DELETE ANNOUNCEMENT
app.delete("/api/announcements/:id", requireAuth, async (req, res) => {
  if ((req.user.adminlevel || 0) < 7) return sendError(res, 403, "Forbidden");
  try {
    await pool.query("DELETE FROM announcements WHERE id=?", [req.params.id]);
    res.json({ success: true });
  } catch (err) {
    console.error(err);
    sendError(res, 500, "Server error");
  }
});

// DELETE UPDATE
app.delete("/api/updates/:id", requireAuth, async (req, res) => {
  if ((req.user.adminlevel || 0) < 1) return sendError(res, 403, "Forbidden");
  try {
    await pool.query("DELETE FROM updates WHERE id=?", [req.params.id]);
    res.json({ success: true });
  } catch (err) {
    console.error(err);
    sendError(res, 500, "Server error");
  }
});

app.listen(5000, () =>
  console.log("✅ Backend running on http://localhost:5000")
);