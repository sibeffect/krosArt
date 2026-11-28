<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Krosart API',
    version: '1.0.0',
    description: "Documentation de l'API Krosart"
)]
class OpenApiSpec
{
    #[OA\Get(
        path: '/api/health',
        summary: 'Health check',
        tags: ['System'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OK'
            ),
        ],
    )]
    public function health(): void {}
}
