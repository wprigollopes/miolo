<?
class frmMain extends Form
{
	const FACIL=10;
	const MEDIO=5;
	const DIFICIL=3;

    public function __construct()
    {   
        parent::__construct('Hangman Game!'); 
        $this->setFormCSS(0,470,0,0,0,'relative');
        $this->eventHandler();
	}

    public function createFields()
    {
        $options = array(
           new RadioButton('level','1','',true,'Fácil. Você pode errar 10 vezes.'),
           new RadioButton('level','2','',false,'Médio. Você pode errar 5 vezes.'),
           new RadioButton('level','3','',false,'Difícil. Você pode errar 3 vezes.')
        );
        $link = $this->manager->getCurrentURL();
        $guesses = $this->getGuessLetters();
 		for($letter=65;$letter<=90;++$letter)
		{
            $char = chr($letter);
		    $letters[] = $button = new LinkButton("guess$char", $char, $link);
            $button->attachEventHandler('click','OnLetterClick',$char); 
            if (strpos($guesses,$char,$pos) !== false)
            {
                $button->color = 'gray';
            }
		}
        $level = array(
            new RadioButtonGroup('levelGroup','', $options,'1', 'vertical','none'),
            new MButton('btnPlay', 'Jogar!')
        );
        $guess = array(
           new TextHeader('choice','1','Escolha uma letra:'),
           new Text('guessWord',''),
           new Spacer('20px'),
           new Text('guessErrors',''),
           new LinkButtonGroup('letters','',$letters,'horizontal','none'),
           new LinkButton('giveUp','Desistir?',$link)
        );
        $lose = array(
           new TextHeader('lose','1','Você perdeu!'),
           new Text('word',''),
           new Spacer('20px'),
           new LinkButton('again','Jogar novamente?',$link)
        );
        $win = array(
           new TextHeader('win','1','Parábens, você ganhou!', 'blue'),
           new Text('word',''),
           new Spacer('20px'),
           new LinkButton('again','Jogar novamente?',$link)
        );
        $fields = array(
           new Label('Você deve descobrir a palavra, escolhendo uma letra de cada vez. Se você errar acima do limite, você perde!'),
           new BaseGroup('choiceGroup','Escolha o nível', $level, 'vertical','css'),
           new BaseGroup('guessGroup','', $guess, 'vertical','css'),
           new BaseGroup('loseGroup','', $lose, 'vertical','css'),
           new BaseGroup('winGroup','', $win, 'vertical','css')
        );
        $this->setFields($fields);
        $this->btnPlay->attachEventHandler('click','OnBtnPlayClick');
        $this->giveUp->attachEventHandler('click','OnGiveUp');
        $this->setStatus('level');
        $this->defaultButton = false;
    } 

    public function setLevel($level)
    {
        switch ($level)
        {
            case '1': $this->page->setViewState('level',self::FACIL); break;
            case '2': $this->page->setViewState('level',self::MEDIO); break;
            case '3': $this->page->setViewState('level',self::DIFICIL); break;
        }
    }

    public function getLevel()
    {
        return $this->page->getViewState('level');
    }

    public function setErrors($errors)
    {
        $this->page->setViewState('errors', $errors);
    }

    public function getErrors()
    {
        return $this->page->getViewState('errors');
    }

    public function setStatus($status)
    {
        if ($status == 'level')
        {
           $this->setFieldAttr('guessGroup','visible',false); 
           $this->setFieldAttr('loseGroup','visible',false); 
        }
        elseif  ($status == 'play')
        {
           $this->setFieldAttr('choiceGroup','visible',false); 
           $this->setFieldAttr('btnPlay','visible',false); 
           $this->setFieldAttr('loseGroup','visible',false); 
           $this->setFieldAttr('guessGroup','visible',true); 
        }
        elseif  ($status == 'lose')
        {
           $this->setFieldAttr('choiceGroup','visible',false); 
           $this->setFieldAttr('btnPlay','visible',false); 
           $this->setFieldAttr('guessGroup','visible',false); 
           $this->setFieldAttr('loseGroup','visible',true); 
        }
    }

    public function setWord($word)
    {
        $this->page->setViewState('word', $word);
    }

    public function getWord()
    {
        return $this->page->getViewState('word');
    }

    public function setGuessWord($guessWord)
    {
        $this->page->setViewState('guessword', $guessWord);
        for($i = 0, $w = ''; $i < strlen($guessWord); $w .= $guessWord[$i] . '&nbsp;', $i++);
		$this->setFieldValue('guessWord', $w);
    }

    public function getGuessWord()
    {
        return $this->page->getViewState('guessword');
    }

    public function setGuessLetters($guessLetters)
    {
        $this->page->setViewState('guessletters', $guessLetters);
    }

    public function getGuessLetters()
    {
        return $this->page->getViewState('guessletters');
    }

    public function setGuessErrors()
    {
        $this->setFieldValue('guessErrors','Você tem ' . $this->getErrors() . ' erros em um máximo de ' . $this->getLevel() . '.');
    }
 
    public function onBtnPlayClick($sender)
    {
        $level = $this->levelGroup->getValue();
		$this->setLevel($level);
        $file = $this->manager->getModulePath('hangman','etc/words.txt');
		$words = preg_split("/[\s,]+/",file_get_contents($file));
		do
		{
			$i = rand(0,count($words)-1);
			$word = $words[$i];
		} while(strlen($word)<5 || !preg_match('/^[a-z]*$/i',$word));
		$word=strtoupper($word);

        $guessWord = str_repeat('_',strlen($word));
        $this->setGuessWord($guessWord);
        $this->setErrors(0);
		$this->setWord($word);
        $this->setGuessErrors();
        $this->setGuessLetters('');
        $this->setStatus('play');
    }

    public function onLetterClick($sender, $param)
    {
        $sender->color = 'blue';
		$letter = $param;
		$word = $this->getWord();
		$guessWord = $this->getGuessWord();
        $this->setStatus('play');
		$pos = 0;
		$success = false;
		while (($pos = strpos($word,$letter,$pos)) !== false)
		{
			$guessWord[$pos] = $letter;
			$success = true;
			$pos++;
		}
  		$this->setGuessWord($guessWord);
		if ($success)
		{
			if ($guessWord === $word)
			{
               $this->setStatus('win');
			}
		}
		else
		{
			$errors = $this->getErrors() + 1;
			$this->setErrors($errors);
			if ($errors >= $this->getLevel())
				$this->onGiveUp(null,null);
		}
        $this->setGuessLetters($this->getGuessLetters() . $letter);
        $this->setGuessErrors();
    }

    public function onGiveUp($sender, $param)
    {
        $this->setGuessLetters('');
		$this->setFieldValue('word', 'A palavra era: ' . $this->getWord());
        $this->setStatus('lose');
    }

}
