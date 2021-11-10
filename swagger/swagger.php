<?php
use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="API Bilemo", version="0.1")
 * @OA\Server(
 *     url="http://localhost:8000/swagger",
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