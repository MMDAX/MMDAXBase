<?php

namespace MMDAXBase\Service;

use Zend\Mail\Transport\Smtp;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\View\Model\ViewModel;
use Zend\View\View;

class Mail
{
    /**
     *
     * @var SmtpTransport 
     */
    protected $transportSmtp;

    /**
     *
     * @var View 
     */
    protected $view;

    /**
     *
     * @var MimePart 
     */
    private $mimePart;

    /**
     *
     * @var MimeMessage 
     */
    private $mimeMessage;

    /**
     *
     * @var ViewModel
     */
    private $viewModel;

    /**
     *
     * @var string 
     */
    protected $template;

    /**
     *
     * @var string 
     */
    protected $subject;

    /**
     *
     * @var string 
     */
    protected $to;

    /**
     *
     * @var array 
     */
    protected $data;

    /**
     *
     * @var Message 
     */
    protected $message;

    /**
     * 
     * @param Smtp $transportSmtp
     * @param View $view
     * @param Message $message
     * @param MimePart $mimePart
     * @param MimeMessage $mimeMessage
     */
    public function __construct(Smtp $transportSmtp, View $view, ViewModel $viewModel, Message $message, MimePart $mimePart, MimeMessage $mimeMessage)
    {
        $this->transportSmtp = $transportSmtp;
        $this->view = $view;
        $this->message = $message;
        $this->mimePart = $mimePart;
        $this->mimeMessage = $mimeMessage;
        $this->viewModel = $viewModel;
    }

    /**
     * 
     * @param string $template
     * @return \MMDAXBase\Mail\Mail
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * 
     * @param array $data
     * @return \MMDAXBase\Mail\Mail
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * 
     * @param string $subject
     * @return \MMDAXBase\Mail\Mail
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * 
     * @param string $to
     * @return \MMDAXBase\Mail\Mail
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * 
     * @param type $template
     * @param array $data
     * @return string
     */
    private function renderView()
    {
        $this->viewModel->setOption('has_parent', true);
        $this->viewModel->setTemplate($this->template);
        $this->viewModel->setVariables($this->data);

        return $this->view->render($this->viewModel);
    }

    /**
     * 
     * @return \MMDAXBase\Mail\Mail
     */
    public function prepare()
    {
        $this->mimePart->setType("text/html");
        $this->mimePart->setContent($this->renderView());
        $this->mimeMessage->setParts(array($this->mimePart));
        $this->message->addFrom($this->transportSmtp->getOptions()->toArray()['connection_config']['from'])
                ->addTo($this->to)
                ->setSubject($this->subject)
                ->setBody($this->mimeMessage);

        return $this;
    }

    /**
     * 
     * @return boolean
     */
    public function send()
    {
        $this->transportSmtp->send($this->message);
    }
}
