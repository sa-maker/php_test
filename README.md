# PHP_Test

A 2 part test to see if I can code basic array function as well as a API call. It was done as a technical test for a job interview in 2019. I used php for the solution as that was what the client used.
The code you are looking at does not work as it relies on a API server that does not exist anymore and requieres a google maps key that has been removed.

# Requierements:

Please complete the following technical test:

-- Part A --

John works for a company that makes boxes but he also has a second job taking calls. His main job is to assemble boxes for Box Tech and he gets paid by the second to do this. His second job is to receive phone calls from Call Tech during the day and he gets paid for every second that he is on his phone. To bill for his time he records the times for when he starts and ends building boxes during the day and he also has records of the start and end times of the calls he receives. Sometime John gets calls while he is building boxes and when he is on his phone he is obviously not building boxes so to work out how much he should be billing Box Tech at the end of the month he removes the periods that he was one the phone from the periods that he was building boxes.

e.g. If he was building boxes from 10:00am to 10:30am and he took a call from 10:15am to 10:25am then he was only building boxes between 10:00am and 10:15am as well as between 10:25am and 10:30am. And this is what he needs to submit to Box Tech.

Write a function in PHP (that takes in two arrays made up of date/time periods defined by Start and End times) and removes the times in the one array from the other.

Remember that John can log that he's stopped making boxes whiles he's talking on the phone, he can be on the phone while he isn't making boxes and he could also accidentally log that he's started making boxes while he is still on the phone. Basically any combination of time periods is possible.

In the example above the arrays John has would would be:

$box_times = array(array('Start' => '2019/03/01 10:00:00', 'End' => '2019/03/01 10:30:00'));
$call_times = array(array('Start' => '2019/03/01 10:15:00', 'End' => '2019/03/01 10:25:00'));

Your function should return:

array(
array('Start' => '2019/03/01 10:00:00', 'End' => '2019/03/01 10:15:00'),
array('Start' => '2019/03/01 10:25:00', 'End' => '2019/03/01 10:30:00')
);

Your function should look something like this:

function your_function($box_times, $call_times)
{

<-- your code here ->>

return $output_times;
}

An example of PHP code to generate some sample arrays:

$box_times = array();
$box_times [] = array('Start' => '2019/03/01 10:00:00', 'End' => '2019/03/01 10:30:00');
$box_times [] = array('Start' => '2019/03/01 14:30:00', 'End' => '2019/03/01 15:00:00');
$box_times [] = array('Start' => '2019/03/01 18:00:00', 'End' => '2019/03/01 18:46:13');

$call_times = array();
$call_times [] = array('Start' => '2019/03/01 10:15:00', 'End' => '2019/03/01 10:25:00');
$call_times [] = array('Start' => '2019/03/01 14:15:00', 'End' => '2019/03/01 14:35:00');
$call_times [] = array('Start' => '2019/03/01 15:00:00', 'End' => '2019/03/01 19:00:00');

-- Part B --

We have created an API for you to extract data from our system. Your task is to create a web site that allows a user to see where their vehicles have been.

You need to create a single webpage that has a list of all the vehicles in a user's fleet together with a map to display the trips that they have made. When the user clicks on a vehicle in the list they should be shown a calendar widget that will allow them to select a range of days. Once that has been done, the map should display all of the trips that the vehicle has done during those days. For each trip, a start maker, end marker and straight line drawn between them is fine. Ideally the markers and the line should look different for each trip and have some sort of visible number/colour to easily identify them.

The API has the following endpoints:

http://test_url/unit_list (this will fail)

and

http://test_url/trips (this will fail)

Your auth key for using the API is: AUTH_CODE // this will fail

The unit_list endpoint expects you to POST your auth key as the variable "auth" and returns a JSON object with a list of vehicles.

The trips endpoint expects you to POST your auth key as the variable "auth" as well as the variables "uid", "from" and "to". The "uid" field is the unique id of the vehicle that you got from the object returned from the unit_list endpoint and the "from" and "to" fields are date/time stamps in the format: "2019/03/01 12:00:00".

You can create the interface in any language/framework you would like.

Please supply us with your finished code as well as an online demo where we can see your implementation.
