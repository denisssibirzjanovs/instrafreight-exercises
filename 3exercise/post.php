<?php

class KeywordExtractor {

  public $sText;

  public $sFormattedText;

  public $aStopWords;

  /**
   * KeywordExtractor constructor.
   * @param $sText
   */
  function __construct($sText)
  {
    $this->sText = $sText;
    $this->setStopWords();
    $this->setFormatString();
  }

  /**
   * Takes care of German alphabet too
   * @param $sText
   * @param string $sDelimiter
   * @return string
   */
  public function formatString($sText, $sDelimiter = ' ')
  {
    $sText = preg_replace("/'|’/u", '', $sText);
    $sText = preg_replace('~[^\\pL\d]+~u', $sDelimiter, $sText);
    $sText = trim($sText, '-');
    $sText = iconv('utf-8', 'us-ascii//TRANSLIT', $sText);
    return strtolower($sText);
  }

  public function setFormatString()
  {
    $this->sFormattedText = $this->formatString($this->sText);
  }

  public function setStopWords()
  {
    $this->aStopWords = explode(PHP_EOL, file_get_contents('stopwords.txt'));
    foreach ($this->aStopWords as &$sWord)
    {
      $sWord = $this->formatString($sWord, '');
    }
  }

  /**
   * @return array
   */
  public function getTopTenKeywords()
  {
    $aCount = array_count_values(explode(' ', $this->sFormattedText));

    arsort($aCount);

    foreach ($aCount as $sTextWord => $iNb)
    {
      if (in_array($sTextWord, $this->aStopWords) || strlen($sTextWord) < 2)
      {
        unset($aCount[$sTextWord]);
      }
    }

    return array_slice($aCount, 0, 10);
  }
}


$oKeywordExtractor = new KeywordExtractor(file_get_contents('post1.md'));

var_dump($oKeywordExtractor->getTopTenKeywords());