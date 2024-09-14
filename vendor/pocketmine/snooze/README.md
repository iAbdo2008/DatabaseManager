# Snooze
Event-driven thread notification management library for code using the pthreads extension

## Use cases
ext-pthreads currently doesn't make it conveniently possible to `wait()` for notifications from multiple threads simultaneously.
This library allows you to do that using a `SleeperHandler`.

Every thread must receive its own `SleeperNotifier` (since they are thread-safe, you can share notifiers between threads, but it's recommended not to).
The thread should call `wakeupSleeper()` on its `SleeperNotifier`, which will cause the thread waiting on `SleeperHandler` to wake up and process whatever notification was delivered.

It's similar to using the `select()` system call on an array of sockets or file descriptors, but with threads instead.

## Example

```php
class NotifyingThread extends \pmmp\thread\Thread{
	public function __construct(
	    private \pocketmine\snooze\SleeperHandlerEntry $sleeperEntry,
	    private \pmmp\thread\ThreadSafeArray $buffer
	){}

	public function run() : void{
		$stdin = fopen('php://stdin', 'r');
		$notifer = $this->sleeperEntry->createNotifier();
		while(true){
			echo "Type something and press ENTER:\n";
			//do whatever you're doing
			$line = fgets($stdin); //blocks until the user enters something

			//add the line to the buffer
			$this->buffer[] = $line;

			//send a notification to the main thread to tell it that we read a line
			//the parent thread doesn't have to be sleeping to receive this, it'll process it next time it tries to go
			//back to sleep
			//if the parent thread is sleeping, it'll be woken up to process notifications immediately.
			$notifer->wakeupSleeper();
		}
	}
}

$sleeper = new \pocketmine\snooze\SleeperHandler();

$buffer = new \pmmp\thread\ThreadSafeArray();
$sleeperEntry = $sleeper->addNotifier(function() use($buffer) : void{
	//do some things when this notifier sends a notification
	echo "Main thread got line: " . $buffer->shift();
});

$thread = new NotifyingThread($sleeperEntry, $buffer);
$thread->start();

while(true){
	$start = microtime(true);
	//do some work that you do every tick

	//process any pending notifications, then try to sleep 50ms until the next tick
	//this may wakeup at any time to process received notifications
	//if it wakes up and there is still time left to sleep before the specified time, it will go back to sleep again
	//until that time, guaranteeing a delay of at least this amount
	//if there are notifications waiting when this is called, they'll be processed before going to sleep
	$sleeper->sleepUntil($start + 0.05);
}

while(true){
	//alternatively, if you want to only wait for notifications and not tick:
	//but from the pthreads rulebook, only ever wait FOR something!
	//this will wait indefinitely until something wakes it up, and then return immediately
	$sleeper->sleepUntilNotification();
}

$thread->join();
//Unregister the notifier when you're done with it
$sleeper->removeNotifier($sleeperEntry->getNotifierId());
```
