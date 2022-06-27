# Business Hours

 Business Hours is a Statamic addon that gives you the option to easily create, manage and display business hours in a modern and stylish layout.

## Features

- Set opening and closing times for each day of the week separately.
- Fields to indicate if you are currently open or closed
- Conditionals to display a text only when you are open or closed
- Comes unstyled, bring your own styling
- Supports `special dates` e.g: holidays, company wide vacations etc
- Use different time zones and languages
- Beautiful & user-friendly settings screen for you, or your client
- Localizable fields for easy translation in any language

## How to Install

You can search for this addon in the `Tools > Addons` section of the Statamic control panel and click **install**, or run the following command from your project root:

``` bash
composer require lucky-media/business-hours
```

## How to Use

On your frontend you can make use of the `business_hours` tag to iterate through the hours.


```
{{ business_hours }}
  
  {{ weekday }}

  {{ closed }}

  {{ 24_hours }}

  {{ start_time }}
  
  {{ end_time }}

  {{ is_open }}

{{ /business_hours }}
```

Params:

* `weekday` (string)
  * Weekday label
* `closed` (boolean)
  * Is the business closed on this day
* `24_hours` (boolean)
  * Is the business working 24hrs on this day
* `start_time` (string)
  * What time does the business start on this day
* `end_time` (string)
  * What time does the business end on this day
* `is_open` (boolean)
  * Is the business open at this moment


If you need to display the exeptions you can use the following:

```antlers
{{ business_hours:exception }}

 {{ reason }}

 {{ start_date }}

 {{ end_date }}

{{ /business_hours:exception }}
```

Params:

* `reason` (bard)
  * Bard field to write down the reason for the exception
* `start_date` (date)
  * Start date of the exception
* `end_date` (date)
  * End date of the exception


## Common Gotcha's
- Make sure on your `config/app.php` you have the correct timezone.
- You can either display times on 24 hour format or 12 hour format, you can change this on `config/statamic/business_hours.php`
- Make sure on CP the `start_time` and `end_time` are entered in 24 hour format. There is also validation if the time is not in this format.

## Examples
Check the example below and the code snippet. Please keep in mind that the examples use Tailwind CSS for styling.

![Screenshot 2022-02-08 at 13 21 11](https://user-images.githubusercontent.com/11158157/152985979-aec8c318-7774-419d-b591-c44a66ab544a.png)

```antlers
<div>
    {{ business_hours:exception }}
        <div class="w-full p-5 mb-5 text-center text-white bg-red-600 rounded">
            {{ reason }}
        </div>
    {{ /business_hours:exception }}


    <div class="grid grid-cols-2 gap-4">
        {{ business_hours }}
            <p class="{{ is_past ?= 'line-through opacity-60' }}">
                {{ weekday }}
            </p>

            <p>
                {{ if closed }}
                    <span class="p-2 text-xs text-white uppercase bg-red-500 rounded">closed</span>
                {{ /if }}

                {{ if !closed and !24_hours }}
                    <span class="{{ is_past ?= 'line-through opacity-60' }}">
                        {{ start_time }} - {{ end_time }}
                    </span>
                {{ /if }}

                {{ if 24_hours }}
                    <span class="p-2 text-xs text-white uppercase bg-purple-500 rounded">
                        24hrs
                    </span>
                {{ /if }}

                {{ if is_open }}
                    <span class="p-2 text-xs text-white uppercase bg-green-500 rounded">
                        open now
                    </span>
                {{ /if }}
            </p>
        {{ /business_hours }}
    </div>
</div>
```
