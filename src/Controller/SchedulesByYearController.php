<?php
declare(strict_types = 1);

namespace App\Controller;

use BBC\ProgrammesPagesService\Domain\ApplicationTime;
use BBC\ProgrammesPagesService\Domain\Entity\Service;
use BBC\ProgrammesPagesService\Domain\ValueObject\Pid;
use Cake\Chronos\Date;

class SchedulesByYearController extends BaseController
{
    public function __invoke(Service $service, string $year)
    {
        $this->setContext($service);

        $startOfYear = Date::createFromFormat('Y|', $year, ApplicationTime::getLocalTimeZone())->firstOfYear();
        $viewData = ['start_of_year' => $startOfYear, 'service' => $service];

        // If the service is not active at all over the year, then the status code should be 404, so
        // that search engines do not index thousands of empty pages
        if (!$this->serviceIsActiveDuringYear($service, $startOfYear)) {
            $this->response()->setStatusCode(404);
        }
        return $this->renderWithChrome('schedules/by_year.html.twig', $viewData);
    }

    private function serviceIsActiveDuringYear(Service $service, Date $startOfYear): bool
    {
        return (!$service->getStartDate() || $service->getStartDate() <= $startOfYear->endOfYear()) && (!$service->getEndDate() || $startOfYear < $service->getEndDate());
    }
}