<?php

namespace Seb\Platform\Web;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

abstract class BaseController
{
    public function __construct(
        protected Request $request,
        protected Response $response,
    ) {}
}
