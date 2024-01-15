<?php

namespace Espo\Modules\ConvertToUkrWordsFunction\FormulaFunctions;

use Espo\Core\Formula\{
    Functions\BaseFunction,
    ArgumentList,
};
class ConvertToUkrWords extends BaseFunction
{
    protected $units = array(
        "", 
        "один", 
        "два", 
        "три", 
        "чотири", 
        "п'ять", 
        "шість", 
        "сім", 
        "вісім", 
        "дев'ять"
    );
    protected $decones = array( 
        '01' => "одна", 
        '02' => "дві", 
        '03' => "три", 
        '04' => "чотири", 
        '05' => "п'ять", 
        '06' => "шість", 
        '07' => "сім", 
        '08' => "вісім", 
        '09' => "дев'ять", 
        10 => "десять", 
        11 => "одинадцять", 
        12 => "дванадцять", 
        13 => "тринадцять", 
        14 => "чотирнадцять", 
        15 => "п'ятнадцять", 
        16 => "шістнадцять", 
        17 => "сімнадцять", 
        18 => "вісімнадцять", 
        19 => "дев'ятнадцять" 
    );

    protected $ones = array( 
        0 => " ",
        1 => "одна",     
        2 => "дві", 
        3 => "три", 
        4 => "чотири", 
        5 => "п'ять", 
        6 => "шість", 
        7 => "сім", 
        8 => "вісім", 
        9 => "дев'ять", 
        10 => "десять", 
        11 => "одинадцять", 
        12 => "дванадцять", 
        13 => "тринадцять", 
        14 => "чотирнадцять", 
        15 => "п'ятнадцять", 
        16 => "шістнадцять", 
        17 => "сімнадцять", 
        18 => "вісімнадцять", 
        19 => "дев'ятнадцять" 
    ); 

    protected $tens = array( 
        0 => "",
        2 => "двадцять", 
        3 => "тридцять", 
        4 => "сорок", 
        5 => "п'ятдесят", 
        6 => "шістдесят", 
        7 => "сімдесят", 
        8 => "вісімдесят", 
        9 => "дев'яносто" 
    ); 

    protected $hundreds = array( 
        "", 
        "сто", 
        "двісті", 
        "триста",  
        "чотириста", 
        "п'ятсот", 
        "шістсот", 
        "сімсот", 
        "вісімсот", 
        "дев'ятсот" 
    ); 

    protected $thousands = array(
        "", 
        "тисяча", 
        "тисячі", 
        "тисяч" 
    );
    protected $millions = array( 
        "", 
        "мільйон", 
        "мільйона", 
        "мільйонів" 
    );

    protected $billions = array( 
        "", 
        "мільярд", 
        "мільярда", 
        "мільярдів" 
    );

    public function process(ArgumentList $args)
    {
        if (count($args) < 1) {
            $this->throwTooFewArguments();
        }

        $value = $this->evaluate($args[0]);

        if (!is_numeric($value)) {
            return null;
        }

            $value = number_format($value, 2, ".", ",");
            $value_arr = explode(".", $value);
            $wholenum = $value_arr[0];
            $decnum = $value_arr[1];
    
            $rettxt = "";
            $wholenum = str_replace(',', '', $wholenum);
            $wholenum = intval($wholenum);
    
            if ($wholenum == 0) {
                $rettxt = "нуль гривень";
            } else {
                $currency = "";
                $lastDigitWholenNum = $wholenum % 10;
                $lastTwoDigitsWholenNum = $wholenum % 100;
    
                $billionsNum = intval($wholenum / 1000000000);
                $wholenum %= 1000000000;

                if ($billionsNum > 0) {
                    $numberTxt = '';

                    if ($billionsNum < 10) {
                        $numberTxt .= $this->units[$billionsNum];
                    } elseif ($billionsNum < 20) {
                        $numberTxt .= $this->ones[$billionsNum];
                    } elseif ($billionsNum < 100) {
                        $numberTxt .= isset($this->tens[substr($billionsNum, 0, 1)]) ? $this->tens[substr($billionsNum, 0, 1)] : '';
                        $numberTxt .= " " . $this->units[substr($billionsNum, 1, 1)];
                    } else {
                        $numberTxt .= $this->hundreds[substr($billionsNum, 0, 1)];

                        $remainder = $billionsNum % 100;
                        if ($remainder < 20) {
                            $numberTxt .= " " . $this->ones[$remainder];
                        } else {
                            $numberTxt .= " " . (isset($this->tens[substr($billionsNum, 1, 1)]) ? $this->tens[substr($billionsNum, 1, 1)] : '');
                            $numberTxt .= " " . $this->units[substr($billionsNum, 2, 1)];
                        }
                    }

                    $numberCurrency = '';

                    $lastDigitNumber = $billionsNum % 10;
                    $lastTwoDigitsNumber = $billionsNum % 100;

                    switch (true) {
                        case ($lastTwoDigitsNumber >= 11 && $lastTwoDigitsNumber <= 19):
                            $numberCurrency = ($billionsNum == 1) ? $this->billions[1] : $this->billions[3];
                            break;
                        case ($lastDigitNumber == 1):
                            $numberCurrency = $this->billions[1];
                            break;
                        case (in_array($lastDigitNumber, [2, 3, 4])):
                            $numberCurrency = $this->billions[2];
                            break;
                        default:
                            $numberCurrency = $this->billions[3];
                    }

                    $rettxt .= $numberTxt . " " . $numberCurrency . " ";
                }


                $millionsNum = intval($wholenum / 1000000);
                $wholenum %= 1000000;
                
                if ($millionsNum > 0) {
                    $numberTxt = '';
                
                    if ($millionsNum < 10) {
                        $numberTxt .= $this->units[$millionsNum];
                    } elseif ($millionsNum < 20) {
                        $numberTxt .= $this->ones[$millionsNum];
                    } elseif ($millionsNum < 100) {
                        $numberTxt .= isset($this->tens[substr($millionsNum, 0, 1)]) ? $this->tens[substr($millionsNum, 0, 1)] : '';
                        $numberTxt .= " " . $this->units[substr($millionsNum, 1, 1)];
                    } else {
                        $numberTxt .= $this->hundreds[substr($millionsNum, 0, 1)];
                
                        $remainder = $millionsNum % 100;
                        if ($remainder < 20) {
                            $numberTxt .= " " . $this->ones[$remainder];
                        } else {
                            $numberTxt .= " " . (isset($this->tens[substr($millionsNum, 1, 1)]) ? $this->tens[substr($millionsNum, 1, 1)] : '');
                            $numberTxt .= " " . $this->units[substr($millionsNum, 2, 1)];
                        }
                    }
                
                    $numberCurrency = '';
                
                    $lastDigitNumber = $millionsNum % 10;
                    $lastTwoDigitsNumber = $millionsNum % 100;
                
                    switch (true) {
                        case ($lastTwoDigitsNumber >= 11 && $lastTwoDigitsNumber <= 19):
                            $numberCurrency = ($millionsNum == 1) ? $this->millions[1] : $this->millions[3];
                            break;
                        case ($lastDigitNumber == 1):
                            $numberCurrency = $this->millions[1];
                            break;
                        case (in_array($lastDigitNumber, [2, 3, 4])):
                            $numberCurrency = $this->millions[2];
                            break;
                        default:
                            $numberCurrency = $this->millions[3];
                    }
                
                    $rettxt .= $numberTxt . " " . $numberCurrency . " ";
                }
    
    
                $thousandsNum = intval($wholenum / 1000);
                $wholenum %= 1000;
                
                if ($thousandsNum > 0) {
                    $numberTxt = '';
                
                    if ($thousandsNum < 20) {
                        $numberTxt .= $this->ones[$thousandsNum];
                    } elseif ($thousandsNum < 100) {
                        $numberTxt .= isset($this->tens[substr($thousandsNum, 0, 1)]) ? $this->tens[substr($thousandsNum, 0, 1)] : '';
                        $numberTxt .= " " . $this->ones[substr($thousandsNum, 1, 1)];
                    } else {
                        $numberTxt .= $this->hundreds[substr($thousandsNum, 0, 1)];
                
                        $remainder = $thousandsNum % 100;
                        if ($remainder < 20) {
                            $numberTxt .= " " . $this->ones[$remainder];
                        } else {
                            $numberTxt .= " " . (isset($this->tens[substr($thousandsNum, 1, 1)]) ? $this->tens[substr($thousandsNum, 1, 1)] : '');
                            $numberTxt .= " " . $this->ones[substr($thousandsNum, 2, 1)];
                        }
                    }
                
                    $numberCurrency = '';
                
                    $lastDigitNumber = $thousandsNum % 10;
                    $lastTwoDigitsNumber = $thousandsNum % 100;
                
                    switch (true) {
                        case ($lastTwoDigitsNumber >= 11 && $lastTwoDigitsNumber <= 19):
                            $numberCurrency = ($thousandsNum == 1) ? $this->thousands[1] : $this->thousands[3];
                            break;
                        case ($lastDigitNumber == 1):
                            $numberCurrency = $this->thousands[1];
                            break;
                        case (in_array($lastDigitNumber, [2, 3, 4])):
                            $numberCurrency = $this->thousands[2];
                            break;
                        default:
                            $numberCurrency = $this->thousands[3];
                    }
                
                    $rettxt .= $numberTxt . " " . $numberCurrency . " ";
                }

                $hundredsNum = intval($wholenum / 100);
                $wholenum %= 100;

                if ($hundredsNum > 0) {
                    $rettxt .= $this->hundreds[$hundredsNum] . " ";
                }

                if ($wholenum > 0) {
                    $numberTxt = '';

                    if ($wholenum < 20) {
                        $numberTxt .= $this->ones[$wholenum];
                    } elseif ($wholenum < 100) {
                        $numberTxt .= $this->tens[substr($wholenum, 0, 1)];
                        $numberTxt .= " " . $this->ones[substr($wholenum, 1, 1)];
                    } else {
                        $numberTxt .= $this->hundreds[substr($wholenum, 0, 1)];
                        $numberTxt .= " " . $this->tens[substr($wholenum, 1, 1)];
                        $numberTxt .= " " . $this->ones[substr($wholenum, 2, 1)];
                    }

                    $rettxt .= $numberTxt . " ";
                }

                switch (true) {
                    case ($lastTwoDigitsWholenNum >= 11 && $lastTwoDigitsWholenNum <= 19):
                        $currency = "гривень";
                        break;
                    case ($lastDigitWholenNum == 1):
                        $currency = "гривня";
                        break;
                    case (in_array($lastDigitWholenNum, [2, 3, 4])):
                        $currency = "гривні";
                        break;
                    default:
                        $currency = "гривень";
                }

                $rettxt = rtrim($rettxt) . " " . $currency;
            }
    
            if ($decnum > 0) {
                $rettxt .= " " . $decnum;
            
                $centsCurrency = ''; 
            
                $lastDigitDecNum = $decnum % 10;
                $lastTwoDigitsDecNum = $decnum % 100;
            
                switch (true) {
                    case ($lastTwoDigitsDecNum >= 11 && $lastTwoDigitsDecNum <= 19):
                        $centsCurrency = "копійок";
                        break;
                    case ($lastDigitDecNum == 1):
                        $centsCurrency = "копійка";
                        break;
                    case (in_array($lastDigitDecNum, [2, 3, 4])):
                        $centsCurrency = "копійки";
                        break;
                    default:
                        $centsCurrency = "копійок";
                }
            
                $rettxt = $rettxt . " " . $centsCurrency;
            }
    
            $rettxt = mb_strtoupper(mb_substr($rettxt, 0, 1)) . mb_substr($rettxt, 1);
    
            return $rettxt;
        }
    }