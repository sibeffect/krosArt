<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

/**
 * Documentation OpenAPI des routes web Fortify, du tableau de bord et des paramètres.
 *
 * Ces routes utilisent des sessions Laravel, le CSRF (X-XSRF-TOKEN / _token) et souvent
 * application/x-www-form-urlencoded plutôt qu'une API JSON pure.
 */
#[OA\Tag(name: 'Authentification', description: 'Connexion, inscription et déconnexion')]
#[OA\Tag(name: 'Mot de passe', description: 'Réinitialisation et confirmation')]
#[OA\Tag(name: 'Vérification e-mail', description: 'Vérification d\'adresse e-mail')]
#[OA\Tag(name: 'Two-factor', description: 'Authentification à deux facteurs')]
#[OA\Tag(name: 'Passkeys', description: 'WebAuthn / passkeys')]
#[OA\Tag(name: 'Tableau de bord', description: 'Espace connecté')]
#[OA\Tag(name: 'Paramètres', description: 'Pages Livewire de paramètres utilisateur')]
#[OA\SecurityScheme(
    securityScheme: 'cookieAuth',
    type: 'apiKey',
    in: 'cookie',
    name: 'laravel_session',
    description: 'Session Laravel (utilisateur connecté via Fortify)',
)]
class FortifySwagger
{
    #[OA\Get(
        path: '/login',
        operationId: 'loginForm',
        summary: 'Formulaire de connexion',
        tags: ['Authentification'],
        responses: [
            new OA\Response(response: 200, description: 'Page HTML de connexion'),
        ],
    )]
    public function loginForm(): void {}

    #[OA\Post(
        path: '/login',
        operationId: 'loginStore',
        summary: 'Connexion',
        tags: ['Authentification'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    required: ['email', 'password'],
                    properties: [
                        new OA\Property(property: 'email', type: 'string', format: 'email'),
                        new OA\Property(property: 'password', type: 'string', format: 'password'),
                        new OA\Property(property: 'remember', type: 'boolean', default: false),
                    ],
                ),
            ),
        ),
        responses: [
            new OA\Response(response: 302, description: 'Redirection après connexion (souvent vers /dashboard)'),
            new OA\Response(response: 422, description: 'Identifiants invalides'),
        ],
    )]
    public function loginStore(): void {}

    #[OA\Post(
        path: '/logout',
        operationId: 'logout',
        summary: 'Déconnexion',
        tags: ['Authentification'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 302, description: 'Redirection après déconnexion'),
        ],
    )]
    public function logout(): void {}

    #[OA\Get(
        path: '/register',
        operationId: 'registerForm',
        summary: 'Formulaire d\'inscription',
        tags: ['Authentification'],
        responses: [
            new OA\Response(response: 200, description: 'Page HTML d\'inscription'),
        ],
    )]
    public function registerForm(): void {}

    #[OA\Post(
        path: '/register',
        operationId: 'registerStore',
        summary: 'Inscription',
        tags: ['Authentification'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    required: ['name', 'email', 'password', 'password_confirmation'],
                    properties: [
                        new OA\Property(property: 'name', type: 'string', maxLength: 255),
                        new OA\Property(property: 'email', type: 'string', format: 'email'),
                        new OA\Property(property: 'password', type: 'string', format: 'password'),
                        new OA\Property(property: 'password_confirmation', type: 'string', format: 'password'),
                    ],
                ),
            ),
        ),
        responses: [
            new OA\Response(response: 302, description: 'Redirection après inscription'),
            new OA\Response(response: 422, description: 'Erreur de validation'),
        ],
    )]
    public function registerStore(): void {}

    #[OA\Get(
        path: '/forgot-password',
        operationId: 'forgotPasswordForm',
        summary: 'Formulaire mot de passe oublié',
        tags: ['Mot de passe'],
        responses: [
            new OA\Response(response: 200, description: 'Page HTML'),
        ],
    )]
    public function forgotPasswordForm(): void {}

    #[OA\Post(
        path: '/forgot-password',
        operationId: 'forgotPasswordStore',
        summary: 'Demande de lien de réinitialisation',
        tags: ['Mot de passe'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    required: ['email'],
                    properties: [
                        new OA\Property(property: 'email', type: 'string', format: 'email'),
                    ],
                ),
            ),
        ),
        responses: [
            new OA\Response(response: 302, description: 'Lien de réinitialisation envoyé'),
        ],
    )]
    public function forgotPasswordStore(): void {}

    #[OA\Get(
        path: '/reset-password/{token}',
        operationId: 'resetPasswordForm',
        summary: 'Formulaire de nouveau mot de passe',
        tags: ['Mot de passe'],
        parameters: [
            new OA\Parameter(name: 'token', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Page HTML'),
        ],
    )]
    public function resetPasswordForm(): void {}

    #[OA\Post(
        path: '/reset-password',
        operationId: 'resetPasswordStore',
        summary: 'Enregistrer le nouveau mot de passe',
        tags: ['Mot de passe'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    required: ['token', 'email', 'password', 'password_confirmation'],
                    properties: [
                        new OA\Property(property: 'token', type: 'string'),
                        new OA\Property(property: 'email', type: 'string', format: 'email'),
                        new OA\Property(property: 'password', type: 'string', format: 'password'),
                        new OA\Property(property: 'password_confirmation', type: 'string', format: 'password'),
                    ],
                ),
            ),
        ),
        responses: [
            new OA\Response(response: 302, description: 'Mot de passe mis à jour'),
        ],
    )]
    public function resetPasswordStore(): void {}

    #[OA\Get(
        path: '/email/verify',
        operationId: 'emailVerifyNotice',
        summary: 'Invitation à vérifier l\'e-mail',
        tags: ['Vérification e-mail'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Page HTML'),
        ],
    )]
    public function emailVerifyNotice(): void {}

    #[OA\Get(
        path: '/email/verify/{id}/{hash}',
        operationId: 'emailVerify',
        summary: 'Vérifier l\'adresse e-mail (lien signé)',
        tags: ['Vérification e-mail'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'hash', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 302, description: 'E-mail vérifié'),
        ],
    )]
    public function emailVerify(): void {}

    #[OA\Post(
        path: '/email/verification-notification',
        operationId: 'emailVerificationNotification',
        summary: 'Renvoyer l\'e-mail de vérification',
        tags: ['Vérification e-mail'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 302, description: 'E-mail de vérification renvoyé'),
        ],
    )]
    public function emailVerificationNotification(): void {}

    #[OA\Get(
        path: '/two-factor-challenge',
        operationId: 'twoFactorChallengeForm',
        summary: 'Formulaire de défi 2FA (après connexion)',
        tags: ['Two-factor'],
        responses: [
            new OA\Response(response: 200, description: 'Page HTML'),
        ],
    )]
    public function twoFactorChallengeForm(): void {}

    #[OA\Post(
        path: '/two-factor-challenge',
        operationId: 'twoFactorChallengeStore',
        summary: 'Valider le code 2FA à la connexion',
        tags: ['Two-factor'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'code', type: 'string', description: 'Code TOTP'),
                        new OA\Property(property: 'recovery_code', type: 'string', description: 'Code de secours'),
                    ],
                ),
            ),
        ),
        responses: [
            new OA\Response(response: 302, description: 'Connexion finalisée'),
        ],
    )]
    public function twoFactorChallengeStore(): void {}

    #[OA\Get(
        path: '/user/confirm-password',
        operationId: 'confirmPasswordForm',
        summary: 'Confirmer le mot de passe (zone sensible)',
        tags: ['Mot de passe'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Page HTML'),
        ],
    )]
    public function confirmPasswordForm(): void {}

    #[OA\Post(
        path: '/user/confirm-password',
        operationId: 'confirmPasswordStore',
        summary: 'Soumettre la confirmation de mot de passe',
        tags: ['Mot de passe'],
        security: [['cookieAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    required: ['password'],
                    properties: [
                        new OA\Property(property: 'password', type: 'string', format: 'password'),
                    ],
                ),
            ),
        ),
        responses: [
            new OA\Response(response: 302, description: 'Mot de passe confirmé'),
        ],
    )]
    public function confirmPasswordStore(): void {}

    #[OA\Get(
        path: '/user/confirmed-password-status',
        operationId: 'confirmedPasswordStatus',
        summary: 'Statut de confirmation de mot de passe',
        tags: ['Mot de passe'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'JSON { confirmed: boolean }'),
        ],
    )]
    public function confirmedPasswordStatus(): void {}

    #[OA\Post(
        path: '/user/two-factor-authentication',
        operationId: 'twoFactorEnable',
        summary: 'Activer l\'authentification à deux facteurs',
        tags: ['Two-factor'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: '2FA activée (configuration en cours)'),
        ],
    )]
    public function twoFactorEnable(): void {}

    #[OA\Delete(
        path: '/user/two-factor-authentication',
        operationId: 'twoFactorDisable',
        summary: 'Désactiver l\'authentification à deux facteurs',
        tags: ['Two-factor'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: '2FA désactivée'),
        ],
    )]
    public function twoFactorDisable(): void {}

    #[OA\Get(
        path: '/user/two-factor-qr-code',
        operationId: 'twoFactorQrCode',
        summary: 'QR code pour l\'application d\'authentification',
        tags: ['Two-factor'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'SVG ou JSON du QR code'),
        ],
    )]
    public function twoFactorQrCode(): void {}

    #[OA\Get(
        path: '/user/two-factor-secret-key',
        operationId: 'twoFactorSecretKey',
        summary: 'Clé secrète TOTP',
        tags: ['Two-factor'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Clé secrète'),
        ],
    )]
    public function twoFactorSecretKey(): void {}

    #[OA\Get(
        path: '/user/two-factor-recovery-codes',
        operationId: 'twoFactorRecoveryCodes',
        summary: 'Lister les codes de secours',
        tags: ['Two-factor'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Liste des codes'),
        ],
    )]
    public function twoFactorRecoveryCodes(): void {}

    #[OA\Post(
        path: '/user/two-factor-recovery-codes',
        operationId: 'twoFactorRegenerateRecoveryCodes',
        summary: 'Régénérer les codes de secours',
        tags: ['Two-factor'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Nouveaux codes'),
        ],
    )]
    public function twoFactorRegenerateRecoveryCodes(): void {}

    #[OA\Post(
        path: '/user/confirmed-two-factor-authentication',
        operationId: 'twoFactorConfirm',
        summary: 'Confirmer l\'activation 2FA avec un code TOTP',
        tags: ['Two-factor'],
        security: [['cookieAuth' => []]],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    required: ['code'],
                    properties: [
                        new OA\Property(property: 'code', type: 'string'),
                    ],
                ),
            ),
        ),
        responses: [
            new OA\Response(response: 200, description: '2FA confirmée'),
        ],
    )]
    public function twoFactorConfirm(): void {}

    #[OA\Get(
        path: '/passkeys/login/options',
        operationId: 'passkeyLoginOptions',
        summary: 'Options WebAuthn pour connexion par passkey',
        tags: ['Passkeys'],
        responses: [
            new OA\Response(response: 200, description: 'JSON PublicKeyCredentialRequestOptions'),
        ],
    )]
    public function passkeyLoginOptions(): void {}

    #[OA\Post(
        path: '/passkeys/login',
        operationId: 'passkeyLogin',
        summary: 'Connexion via passkey',
        tags: ['Passkeys'],
        responses: [
            new OA\Response(response: 302, description: 'Connexion réussie'),
        ],
    )]
    public function passkeyLogin(): void {}

    #[OA\Get(
        path: '/passkeys/confirm/options',
        operationId: 'passkeyConfirmOptions',
        summary: 'Options WebAuthn pour confirmation par passkey',
        tags: ['Passkeys'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'JSON options'),
        ],
    )]
    public function passkeyConfirmOptions(): void {}

    #[OA\Post(
        path: '/passkeys/confirm',
        operationId: 'passkeyConfirm',
        summary: 'Confirmer une action sensible via passkey',
        tags: ['Passkeys'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Action confirmée'),
        ],
    )]
    public function passkeyConfirm(): void {}

    #[OA\Get(
        path: '/user/passkeys/options',
        operationId: 'passkeyRegistrationOptions',
        summary: 'Options d\'enregistrement d\'une passkey',
        tags: ['Passkeys'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'JSON PublicKeyCredentialCreationOptions'),
        ],
    )]
    public function passkeyRegistrationOptions(): void {}

    #[OA\Post(
        path: '/user/passkeys',
        operationId: 'passkeyStore',
        summary: 'Enregistrer une nouvelle passkey',
        tags: ['Passkeys'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 201, description: 'Passkey enregistrée'),
        ],
    )]
    public function passkeyStore(): void {}

    #[OA\Delete(
        path: '/user/passkeys/{passkey}',
        operationId: 'passkeyDestroy',
        summary: 'Supprimer une passkey',
        tags: ['Passkeys'],
        security: [['cookieAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'passkey', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Passkey supprimée'),
        ],
    )]
    public function passkeyDestroy(): void {}

    #[OA\Get(
        path: '/.well-known/passkey-endpoints',
        operationId: 'passkeyWellKnown',
        summary: 'Points de terminaison passkey (well-known)',
        tags: ['Passkeys'],
        responses: [
            new OA\Response(response: 200, description: 'JSON enroll / manage URLs'),
        ],
    )]
    public function passkeyWellKnown(): void {}

    #[OA\Get(
        path: '/dashboard',
        operationId: 'dashboard',
        summary: 'Tableau de bord',
        description: 'Requiert authentification et e-mail vérifié.',
        tags: ['Tableau de bord'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Page HTML du tableau de bord'),
            new OA\Response(response: 302, description: 'Redirection vers login si non connecté'),
        ],
    )]
    public function dashboard(): void {}

    #[OA\Get(
        path: '/settings',
        operationId: 'settingsRedirect',
        summary: 'Redirection vers les paramètres',
        description: 'Redirige vers /settings/profile.',
        tags: ['Paramètres'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 302, description: 'Redirection vers le profil'),
        ],
    )]
    public function settingsRedirect(): void {}

    #[OA\Get(
        path: '/settings/profile',
        operationId: 'settingsProfile',
        summary: 'Paramètres — profil',
        tags: ['Paramètres'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Page Livewire profil'),
        ],
    )]
    public function settingsProfile(): void {}

    #[OA\Get(
        path: '/settings/appearance',
        operationId: 'settingsAppearance',
        summary: 'Paramètres — apparence',
        description: 'Requiert e-mail vérifié.',
        tags: ['Paramètres'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Page Livewire apparence'),
        ],
    )]
    public function settingsAppearance(): void {}

    #[OA\Get(
        path: '/settings/security',
        operationId: 'settingsSecurity',
        summary: 'Paramètres — sécurité (2FA, passkeys, mot de passe)',
        description: 'Requiert e-mail vérifié et confirmation de mot de passe récente pour certaines actions.',
        tags: ['Paramètres'],
        security: [['cookieAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Page Livewire sécurité'),
        ],
    )]
    public function settingsSecurity(): void {}
}
