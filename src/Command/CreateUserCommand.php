<?php

namespace App\Command;

use Awurth\Slim\Helper\Command\SentinelCreateUserCommand;
use Cartalyst\Sentinel\Sentinel;
use Exception;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateUserCommand extends SentinelCreateUserCommand {

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        if (null === $this->getName()) {
            $this->setName('user:create');
        }

        $this
            ->setDescription('Create a new user')
            ->setDefinition([
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
                new InputOption('admin', null, InputOption::VALUE_NONE, 'Set the user as admin')
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('admin')) {
            $role = $this->sentinel->findRoleByName($this->options['admin_role']);
        } else {
            $role = $this->sentinel->findRoleByName($this->options['user_role']);
        }

        $user = $this->sentinel->registerAndActivate([
            'email' => $input->getArgument('email'),
            'password' => $input->getArgument('password'),
            'permissions' => []
        ]);

        $role->users()->attach($user);

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = [];

        if (!$input->getArgument('email')) {
            $question = new Question('Please choose an email:');
            $question->setValidator(function ($email) {
                if (empty($email)) {
                    throw new Exception('Email can not be empty');
                }

                return $email;
            });

            $questions['email'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Please choose a password:');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new Exception('Password can not be empty');
                }

                return $password;
            });

            $question->setHidden(true);
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}