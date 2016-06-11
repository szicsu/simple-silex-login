<?php

namespace Login\Command;

use Login\Service\Security\BlackList\Reader\BlackListStatItem;
use Login\Service\Security\BlackList\Reader\BlackListStatReaderInterface;
use Login\Service\Security\BlackList\Util\IpLevelUtil;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BlackListStatCommand extends Command
{
    /**
     * @var BlackListStatReaderInterface
     */
    private $statReader;

    public function __construct(BlackListStatReaderInterface $statReader)
    {
        parent::__construct(null);
        $this->statReader = $statReader;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('login:black-list:stat:show')
            ->setDescription('Show stat of BlackList')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $endDate = new \DateTimeImmutable();
        $startDate = $endDate->modify('-1hour');

        $statData = $this->statReader->findByDateRange($startDate, $endDate);

        $table = new Table($output);
        $table
            ->setHeaders(array('Stat date', 'Failed login', 'Same user', 'IP', 'IP:24', 'IP:16'))
            ->setRows($this->generateTableRows($statData))
            ->render()
        ;
    }

    private function generateTableRows(\SplObjectStorage $statData) : array
    {
        $result = array();

        /** @var \DateTimeInterface $date */
        foreach ($statData as $date) {
            /** @var BlackListStatItem $item */
            $item = $statData[$date];
            $result[] = array(
                $date->format('Y-m-d H:i:s'),
                $item->getGlobalFailedLoginCount(),
                $item->getCaptchaByEmailCount(),
                $item->getCaptchaByIpCountByIpLevel(IpLevelUtil::LEVEL_4),
                $item->getCaptchaByIpCountByIpLevel(IpLevelUtil::LEVEL_3),
                $item->getCaptchaByIpCountByIpLevel(IpLevelUtil::LEVEL_2),
            );
        }

        return $result;
    }
}
