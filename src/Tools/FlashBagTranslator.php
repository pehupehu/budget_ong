<?php

namespace App\Tools;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class FlashBagTranslator
 * @package App\Tools
 */
class FlashBagTranslator
{
    /** @var Session $session */
    private $session;

    /** @var Translator $translator */
    private $translator;

    /** @var array */
    private $messages;

    /**
     * FlashBagTranslator constructor.
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     */
    public function __construct(SessionInterface $session, TranslatorInterface $translator)
    {
        $this->session = $session;
        $this->translator = $translator;
    }

    /**
     * @param $message
     * @param bool $translate
     * @param null $number
     * @param array $params
     * @return string
     */
    private function getMessageTranslate($message, $translate = true, $number = null, $params = [])
    {
        if ($translate && $number !== null) {
            $message = $this->translator->transChoice($message, $number, $params);
        } elseif ($translate) {
            $message = $this->translator->trans($message, $params);
        }

        return $message;
    }

    /**
     * @param $type
     * @param $message
     * @param bool $translate
     * @param null $number
     * @param array $params
     */
    public function add($type, $message, $translate = true, $number = null, $params = [])
    {
        $message = $this->getMessageTranslate($message, $translate, $number, $params);
        $this->session->getFlashBag()->add($type, $message);
    }

    /**
     * @param $type
     * @param $message
     * @param bool $translate
     * @param null $number
     * @param array $params
     */
    public function addGroupMessage($type, $message, $translate = true, $number = null, $params = [])
    {
        $this->messages[$type][] = $this->getMessageTranslate($message, $translate, $number, $params);
    }

    /**
     * @param string $separator
     */
    public function execute($separator = '<br/>')
    {
        foreach ($this->messages as $type => $messages) {
            $this->session->getFlashBag()->add($type, implode($separator, $messages));
        }
    }
}