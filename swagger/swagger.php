<?php
use OpenApi\Annotations\OpenApi as OA;

/**
 * @OA\Info(title="API Bilemo", version="0.1")
 * @OA\Server(
 *     url="http://localhost:8000/",
 *     description="API for Bilemo's Project"
 * )
 * @OA\SecurityScheme(
 *     bearerFormat="JWT",
 *     securityScheme="bearer",
 *     type="apiKey",
 *     in="header",
 *     name="bearer",
 * )
 */