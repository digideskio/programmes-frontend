<?php
declare(strict_types = 1);
namespace App\EventSubscriber;

use App\Translate\TranslateProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ResponseSubscriber implements EventSubscriberInterface
{
    private $translateProvider;

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => [['updateHeaders', 0]],
        ];
    }

    public function __construct(TranslateProvider $translateProvider)
    {
        $this->translateProvider = $translateProvider;
    }

    public function updateHeaders(FilterResponseEvent $event)
    {
        $response = $event->getResponse();

        // Don't run on subrequests, such as when handling exceptions
        if (!$event->isMasterRequest()) {
            return;
        }

        // Always add the following headers to vary on, so that differences in
        // these headers are cached separately. Add these to any already
        // existing value rather than overwriting.
        $response->setVary(['X-CDN', 'X-BBC-Edge-Scheme'], false);

        // X-UA-Compatible header choose what version of Internet Explorer the page should be rendered as.
        // Only affects IE8, 9 and 10
        $response->headers->set('X-UA-Compatible', 'IE=edge');

        $languageCode = $this->translateProvider->getTranslate()->translate('language_code');
        // Content-Language is used to describe the language(s) intended for the audience
        $response->headers->set('Content-Language', $languageCode);

        // X-Webapp is a BBC header to monitor Varnish hit/miss/pass/stale stats
        $response->headers->set('X-Webapp', 'programmes-frontend');

        // X-Cache-Control is a BBC Header, it sets a grace period during which stale content may be served by Varnish
        $response->headers->set('X-Cache-Control', 'stale-while-revalidate=30');

        $event->setResponse($response);
    }
}
