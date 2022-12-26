<?php
declare(strict_types=1);

namespace PhpFidder\Core\Registration\Action;

use Laminas\Diactoros\Response;
use PhpFidder\Core\Registration\Hydrator\UserHydrator;
use PhpFidder\Core\Registration\Validator\RegisterValidator;
use PhpFidder\Core\Renderer\TemplateRendererInterface;
use PhpFidder\Core\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function DI\value;

final class Register
{
    public function __construct(private readonly TemplateRendererInterface $renderer,
    private readonly RegisterValidator $validator,
    private readonly UserHydrator $userHydrator,
    private readonly UserRepository $userRepository
    ){

    }
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $body = [];
        $isPostRequest = $request->getMethod() === 'POST';
        if($isPostRequest){
            $body = $request->getParsedBody();
        }
        $username = $body['username']??'';
        $email = $body['email']??'';
        $password = $body['password']??'';
        $passwordRepeat = $body['passwordRepeat']??'';



        if($isPostRequest && $this->validator->isValid($username,$email,$password,$passwordRepeat))
        {
            $user = $this->userHydrator->hydrate($body);
            $this->userRepository->add($user);
            $this->userRepository->persist();
            return new Response\RedirectResponse('/');
        }

        $body = $this->renderer->render('register',[
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'passwordRepeat' => $passwordRepeat,
            'errors' => $this->validator->getErrors()
        ]);
        $response = new Response();
        $response->getBody()->write($body);
        return $response;
    }
}