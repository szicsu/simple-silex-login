<?php

namespace Login\Service\Security\Captcha;

final class CaptchaDescriptor
{
    /**
     * @var string
     */
    private $question;

    /**
     * @var string
     */
    private $answer;

    /**
     * @param string $question
     * @param string $answer
     */
    public function __construct(string $question, string $answer)
    {
        $this->answer = $answer;
        $this->question = $question;
    }

    /**
     * @return string
     */
    public function getAnswer() :string
    {
        return $this->answer;
    }

    /**
     * @return string
     */
    public function getQuestion() :string
    {
        return $this->question;
    }
}
