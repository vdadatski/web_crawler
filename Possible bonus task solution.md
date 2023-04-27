There was not enough time to implement the solution to the bonus task, but it seemed interesting to me, so I thought about the algorithm and how I would solve it if I had more time.

I would make 2 arrays. An array with a queue of links to visit and an array of visited links.
At the start of the script, I would add the url entered by the user and the value of the search depth to the array.
Then I would run a loop that checks the queue until it is empty and scans the links to get everything necessary from it (headers, number of words, links). I would check the received links for presence in the visited array and if they are not there, I would add them to the queue, reducing the search depth by 1.
If the search depth dropped to 0, then I would not add new links.

For example, we have a url that the user entered in the console and a search depth of 2. The script adds this url to the queue like this ["url" => users_url, "depth" = 2 ];

So at the beginning of the loop:
$queue = [ 0 => ["url" => users_url, "depth" = 2 ]]
$visited = []

On the first iteration, It extracts the link from the queue, crawls it, and writes it to the visited array.
And add links received as a result of scanning to the queue, reducing the search depth by 1.

So after first iteration it will look like that:
$queue = [ 1 => ["url" => link1, "depth" = 1], 2 => ["url" => link2, "depth" = 1], 2 => ["url" => link3, "depth" = 1]]
$visited = [users_url => true]

After second one something like that:

$queue = [ 3 => ["url" => link4, "depth" = 0], 4 => ["url" => link5, "depth" = 0], 5 => ["url" => link3, "depth" = 0]]
$visited = [users_url => true, link1 => true, link2 => true, link3 => true]

At the next iteration, it crawls links, but  doesn't add new ones to the queue, since the search depth has dropped to 0.
We may also come across links that we have already visited.
For example, link3. In this case, the script will skip it, since it is present in the visited array.

So in the end we will have an empty queue and all links in the visited array.
queue = [];
$visited = [users_url => true, link1 => true, link2 => true, link3 => true,  link4 => true,  link5 => true]

It may be worth limiting the search depth value to some number so that the number of links to crawl does not grow too high.
It is also worth writing some kind of logic for visiting relative links. For the script to add the current domain to them.

If the number of links is too large, then we can consider storing the queue and visited pages not in arrays but in the database. Such an optimization should be considered on very large volumes with real numbers on memory usage and compare it with a decrease in the speed of the script to see if it's worth it.
