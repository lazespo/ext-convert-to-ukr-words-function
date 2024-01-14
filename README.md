# ConvertToUkrWords function for EspoCRM

Formula script function for converting amount (or any other numeric value) to ukrainian words.

`amount\convertToUkrWords(VALUE)`

**Examples:**

```
amount\convertToUkrWords(10000.66) // will return `десять тисяч гривень шістдесят шість копійок`
amount\convertToUkrWords(1310440.24) // will return `один мільйон триста десять тисяч чотириста сорок гривень двадцять чотири копійки`
```

## Situational example of use

For Ukrainian documentation, a system for converting amounts into Ukrainian words, taking into account the currency (hryvnia and kopijka), may be useful.

In order for the text value of the amount with hryvnias and kopijkas to be written in the EspoCRM field, you need to:
1. Go to Administration > *entity* > Fields and create a field with the Varchar type (for example, named `wordsAmount`). It is recommended to increase the Max Length value to 300.
2. Go to Administration > *entity* > Formula and use the function:
   ```
   wordsAmount = amount\convertToUkrWords(amount);
   ```
3. (optional) Go to Administration > Entity > Layout, select the layouts on which the field will be displayed, and move it there.
