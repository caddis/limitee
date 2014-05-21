# Limitee 1.0.2

Limit ExpressionEngine titles, text input field types, and textarea field types to a specific character count, with a counter to let you know how many characters are remaining. Our thanks to [MD Character Count] for the inspiration behind this add-on.

[MD Character Count]: http://devot-ee.com/add-ons/md-character-count

## Use

To use, extract and put the limitee directory in your system third_party directory. Navigate to the ExpressionEngine extensions area and enable Limitee.

To set up character limits, click on Limitee's settings from the ExpressionEngine extensions page. This will show you a list of your channels, and the fields available to those channels that are eligible for character count and limiting.

If the field is a Title field, Limitee will display in parentheses what ExpressionEngine’s title character limit is set to.

The same applies to the "text input" field type. The limit you have set on that field in the field settings will be shown in parentheses.

## Soft Limit

A soft limit will not enforce the limit, but will present a character count below the field as the user types. When the user has exhausted the remaining characters, the count will turn red but the user can continue typing and the characters will not actually be limited. This is useful if you don't want to cut off user input but want them to know they should be wrapping it up at this point, but it's okay to go a little bit over.

## Hard Limit

A hard limit will still present a character count below the field as with soft limit, but the limit will be strictly imposed. The user will not be allowed to exceed the set character limit.

## Screen Shots

![Settings](http://files.caddis.co/addons/limitee/settings.jpg)

![Entry](http://files.caddis.co/addons/limitee/publish.jpg)

## License

Copyright 2014 Caddis Interactive, LLC

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

	http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.