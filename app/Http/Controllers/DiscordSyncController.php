namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class DiscordSyncController extends Controller
{
    public function sync(Request $request)
    {
        $username = $request->query('username');
        if (!$username) {
            return response("No username provided", 400);
        }

        // Find the user in DB
        $user = User::where('username', $username)->first();
        if (!$user || !$user->discordid) {
            return response("User not found or no Discord linked", 404);
        }

        // Change nickname in your Discord server
        $discordBotToken = env('DISCORD_BOT_TOKEN');
        $guildId = env('DISCORD_GUILD_ID');

        $response = Http::withHeaders([
            'Authorization' => "Bot {$discordBotToken}",
            'Content-Type' => 'application/json'
        ])->patch("https://discord.com/api/v10/guilds/{$guildId}/members/{$user->discordid}", [
            'nick' => $user->username
        ]);

        if ($response->successful()) {
            return response("OK", 200);
        }

        return response("Failed to update Discord", 500);
    }
}