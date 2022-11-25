<?php

namespace Seb\Platform\Web;

use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Psr\Http\Message\ResponseInterface as Response;

abstract class BaseController
{
    public function __construct(
        protected ServerRequest $request,
        protected Response $response,
    ) {}
}
