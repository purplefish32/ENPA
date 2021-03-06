<?php

// src/Innova/AppBundle/Command/TreeCommand.php
namespace Innova\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Class LoadDevCommand
 *
 * @package Innova\AppBundle\Command
 */
class LoadDevCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('innova:dev-fixtures:load')
            ->setDescription('populate database with devloppement fixtures');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $kernel = $this->getApplication()->getKernel();

        $command = $this->getApplication()->find('doctrine:schema:drop');
        $arguments = array(
            'command' => 'doctrine:schema:drop',
            '--force'  => true,
        );

        $input = new ArrayInput($arguments);
        $command->run($input, $output);

        $command = $this->getApplication()->find('doctrine:schema:update');
        $arguments = array(
            'command' => 'doctrine:schema:update',
            '--force'  => true,
            '-n' => true,
        );

        $input = new ArrayInput($arguments);
        $command->run($input, $output);

        $output->writeln("Loading fixtures...");
        $command = $this->getApplication()->find('doctrine:fixtures:load');
        $arguments = array(
            'command' => 'doctrine:fixtures:load',
            '--append' => true,
            '-n' => true
        );

        $input = new ArrayInput($arguments);
        $command->run($input, $output);

        $output->writeln("Loading dev fixtures...");
        $command = $this->getApplication()->find('doctrine:fixtures:load');
        $arguments = array(
            'command' => 'doctrine:fixtures:load',
            '--fixtures'  => $kernel->locateResource('@InnovaTaxonomyBundle/DataFixtures/Dev/'),
            '--append' => true,
            '-n' => true
        );

        $input = new ArrayInput($arguments);
        $command->run($input, $output);

        $output->writeln("Loading dev fixtures...");
        $command = $this->getApplication()->find('doctrine:fixtures:load');
        $arguments = array(
            'command' => 'doctrine:fixtures:load',
            '--fixtures'  => $kernel->locateResource('@InnovaLearningPathBundle/DataFixtures/Dev/'),
            '--append' => true,
            '-n' => true
        );

        $input = new ArrayInput($arguments);
        $command->run($input, $output);
        $output->writeln('        __                      __
     .-\'  `\'.._...-----..._..-\'`  \'-.
    /                                \
    |  ,   ,\'                \'.   ,  |
     \  \'-/                    \-\'  /
      \'._|          _           |_.\'
         |    /\   / \    /\    |
         |    \/   | |    \/    |
          \        \"/         /
           \'.    ==\'^\'==     .\'
             `\'------------\'`');
    }
}
