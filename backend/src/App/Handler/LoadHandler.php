<?php


namespace App\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\LaminasView\LaminasViewRenderer;
use Mezzio\Plates\PlatesRenderer;
use Mezzio\Router;
use Mezzio\Template\TemplateRendererInterface;
use Mezzio\Twig\TwigRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoadHandler implements RequestHandlerInterface
{

    /** @var string */
    private $containerName;

    /** @var null|TemplateRendererInterface */
    private $template;

    public function __construct(
        string $containerName,
        ?TemplateRendererInterface $template = null
    ) {
        $this->containerName = $containerName;
        $this->template      = $template;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $params = $request->getQueryParams();
        if(!$params['key']){
            return new JsonResponse('Error');
        }
        $response = $this->getTemplate($params);
        return new JsonResponse($response);
    }

    protected function getTemplate(array $key): string
    {
        $tempalate = $this->template->render('templates::'.$key['component'].'/'.$key['key']);
        return $tempalate;
    }
}