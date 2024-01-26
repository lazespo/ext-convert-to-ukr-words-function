# CurrencyToUAWords function for EspoCRM

Formula script function for converting amount (or any other numeric value) to ukrainian words.

`amount\currencyToUAWords(VALUE)`

**Examples:**

```
amount\currencyToUAWords(10000.66) // will return `Десять тисяч гривень 66 копійок`
amount\currencyToUAWords(1310440.24) // will return `Один мільйон триста десять тисяч чотириста сорок гривень 24 копійки`
```

## Situational example of use

For Ukrainian documentation, a system for converting amounts into Ukrainian words, taking into account the currency (hryvnia and kopijka), may be useful.

In order for the text value of the amount with hryvnias and kopijkas to be written in the EspoCRM field, you need to:
1. Go to Administration > *entity* > Fields and create a field with the Varchar type (for example, named `wordsAmount`). It is recommended to increase the Max Length value to 300.
2. Go to Administration > *entity* > Formula and use the function:
   ```
   wordsAmount = amount\currencyToUAWords(amount);
   ```
3. (optional) Go to Administration > Entity > Layout, select the layouts on which the field will be displayed, and move it there.

# CurrencyToUAWords PDF Template helper for EspoCRM

PDF Template helper for convertion amount (or any other numeric value) to ukrainian words.

`{{currencyToUAWords amount_RAW}}`

`amount` is a field name.

Available options:
- `firstLetter` - can be *capital* or *small*
- `words` - can be *abbr* (abbreviated) or *full* 
- `cents` - can be *number* or *string*

By default, custom helper use capital first letter, abbreviated words and kopijkas in number format.

**Examples:**

```
{{currencyToUAWords amount_RAW}} // amount is 234992.20
```

will return `Двісті тридцять чотири тисячі дев'ятсот дев'яносто дві грн. 20 коп.`

```
{{currencyToUAWords amount_RAW firstLetter='small' words='full' cents='string'}}
```

will return `двісті тридцять чотири тисячі дев'ятсот дев'яносто дві гривні двадцять копійок`
