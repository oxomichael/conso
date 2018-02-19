<?php

namespace CarbuBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StatsMonthlyCommand extends ContainerAwareCommand
{

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('carbu:stats:monthly')
            ->setDescription('Calc monthly statistics')
            ->setDefinition(array(
                new InputArgument('month', InputArgument::REQUIRED, 'Month (YYYYMM)'),
            ))
            ->setHelp(<<<'EOT'
The <info>%command.full_name%</info> calculate stats for a month:

  <info>php %command.full_name% 201706</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $month = $input->getArgument('month');



        $output->writeln(sprintf('Month "%s".', $month));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('month')) {
            if (empty($month)) {
                throw new \Exception('Month can not be empty');
            }
        }
    }
}