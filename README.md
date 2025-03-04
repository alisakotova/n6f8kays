## Моделирование работы таксопарка
### Постановка задачи
Существует некий таксопарк на N машиномест. Управляющий управляет количеством мест и составом автомобилей. Автомобили в таксопарке расходуют примерно 10л на 100км поездок. Существует вероятность поломки автомобиля: изначально новый автомобиль может сломаться с вероятностью 0.5% в день, но с каждыми пройденными 1000км она увеличивается на 1%. Таксопарк обслуживает три марки автомобилей: Homba, Luda и Hendai, где Hendai - марка-эталон. Автомобили Luda ломаются в 3 раза чаще, а Homba на 30% экономичнее других. Поломка автомобиля может произойти во время смены, при этом управляющий должен вызвать эвакуатор для доставки автомобиля в таксопарк, где автомобиль будет ремонтироваться 3 дня, занимая свое машиноместо.
Считается, что автомобиль выдается водителю на сутки. Обычный водитель выполняет 10 поездок за смену. Каждая поездка занимает в среднем 7 км. Существует подкатегория водителей “Бывалые”. “Бывалые” могут выполнить на 30% заказов больше, а расход топлива у них меньше на 20%.
Необходимо создать систему классов для моделирования работы таксопарка с любыми входными данными. Моделирование представляет собой не только описание свойств системы, но и динамику их изменения со временем. Результатом моделирования должен стать некий отчет.
Входные данные представляют собой json с заданной структурой:
```php
{
    "park" : {"places" : 30},
    "drivers" : [{"type" : "default"/"pro"}, ...],
    "cars" : [{"km" : 15351, "brand" : "Homba"/"Luda"/"Hendai"}, ...]
}   
```

### Запуск моделирования
Запуск моделирования осуществляется файлом `index.php`. Исходный json задается в файле `data.php`. Лучше запускать в браузере, так как есть html вывод.
В корне проекта лежит pdf с UML диаграммой классов.

### Выходной отчет
Выходной отчет представляет собой json в формате:
```php 
{
    "drivers" : [{"id": 1, "type": "pro"}, ...],
    "cars" : [{"id": 1, "brand": "Luda", "km": 2517},{"id": 2, "brand": "Homba", "km": 12313}, ...],
    "days" : {
                "1" : {
                        "drivers":[{"id": 5, "car_id": 1, "fuel": 7}, {"id": 1, "car_id": 2, "fuel": 4.9}, ...],
                        "cars":[{"id":5, "km": 34391, "is_broken": true}, {"id": 1, "km": 2657, "is_broken": false}, ...]
                      },
                "2" : {
                        "drivers":[{"id": 3, "car_id": 5, "fuel": 7}, {"id": 2, "car_id": 4, "fuel": 7}, ...],
                        "cars":[{"id":5, "km": 34391, "is_broken": true}, {"id": 1, "km": 2657, "is_broken": false}, ...]
                      },
                ...      
             }         
}
```

Я добавила уникальные идентификаторы `id` для каждого водителя и машины, 
чтобы можно было однозначно определять их.

`drivers` и `cars` содержат изначальные данные о водителях и машинах с присвоенными уникальными идентификаторами.
`days` содержит массив дней работы таксопарка с порядковыми номерами дней работы в качестве ключей.
Данные одного дня представляют собой массив c данными каждого водителя за этот день (какую машину водил и сколько топлива потратил) 
и состояниями каждой машины (пробег и рабочее состояние).

Отчет выводится в таблицах html, и после них выводится json.

Информация в отчете структурирована таким образом, чтобы можно было получить данные о состоянии таксопарка по заданным критериям
для каждого дня, для каждого водителя и каждой машины. Например, можно построить такие отчеты: 
* какими машинами пользовался водитель id=3 с 5 по 7 день работы в таксопарке; 
* сколько дней всего заданная машина находилась в ремонте за все время работы таксопарка; 
* сколько топлива израсходовал водитель для каждого бренда машин, которыми он управлял, в процентном соотношении; 
* сколько топлива в среднем в день расходует таксопарк и тд.

Изменить количество дней симуляции работы таксопарка можно в файле `index.php` в константе `TAXISTATION_WORKDAYS_NUMBER` (по умолчанию 10).

### Запуск тестов
`cd taxi_project_root && php codecept.phar run`


Это все!
