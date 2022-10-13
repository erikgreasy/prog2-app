<?php

declare(strict_types=1);

namespace App\Github;

use Illuminate\Support\Facades\Http;

class Client
{
    private string $clientId;
    private string $clientSecret;
    private string $apiBaseUrl = 'https://api.github.com/';
    private string $accessToken;

    public function __construct()
    {
        $this->clientId = config('github.client_id');
        $this->clientSecret = config('github.client_secret');
    }

    private function fetchAccessToken(string $oauthCode): string
    {
        $res = Http::acceptJson()->post('https://github.com/login/oauth/access_token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $oauthCode
        ]);

        return $res->json()['access_token'];
    }

    public function initializeToken(string $oauthCode): void
    {
        $this->accessToken = $this->fetchAccessToken($oauthCode);
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function get(string $path): array
    {
        $url = $this->apiBaseUrl . $path;

        return Http::withToken($this->accessToken)
                    ->acceptJson()
                    ->get($url)
                    ->json();
    }

    public function cloneRepo(string $username): void
    {
        $path = storage_path('app');
        $repoPath = "$path/$username/prog2-repo";

        putenv("PATH=C:\laragon\bin\git\bin");
        $cloneCmd = "git clone https://{$this->accessToken}@github.com/$username/prog2-repo.git $repoPath";
        shell_exec($cloneCmd);
        
        putenv("PATH=C:\\MinGW\\bin");
        $compileCmd = "gcc $repoPath/file01.c -o $repoPath/file01.exe";
        shell_exec($compileCmd);
    }
}
