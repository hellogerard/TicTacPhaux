<?php

class WHLiveTest extends WHComponent {
	protected $navigation;
	protected $message = "Click on a link to set a message";
	protected $messages = array(
							"Foobar",
							"Click me",
							"No Click me",
							"I look cooler in a div",
							"Phaux makes things easy");
	public function __construct(){
		$this->navigation = Object::construct("WHLiveNavigation");
		$this->navigation->
				addWithLabel(Object::construct("WHCounter"),"Counter")->
				addWithLabel(Object::construct("WHFormTest"),"Form Test");
		return $this;
	}
						
	
	public function setMessage($aString){
		$this->message = $aString;
		return $this;
	}

	/*
	** This method is called as a live update message.
	** It should return a tag (or more) that 
	** has an ID that same tag with that same ID
	** should of already been rendered on the page.
	** The contents of the returned tag (or tags)
	** will replace the contents of the tags with that
	** ID that has already been rendered on the Browser 
	*/
	public function renderMessageOn($html){
		return $html->div()->id("message")->with(
			$this->message);
			
	}
	
	public function renderMessageLinkOn($html,$message){
		return $html->anchor()->with($message)->
					/*
					** The following registers a live callback on this anchor
					** onClick tells the object that we want this actions to 
					** exec on the link being clicked
					** the next line $this,"renderMessageOn",array()
					** tells the anchor what message we want to use to rerender a 
					** portion on the page (commented above)
					** 
					** the last line $this,"setMessage",array($message)
					** tells the anchor what callback to run when the action occurs
					*/
						
								liveUpdateWithCallbackOn("onClick",
													$this,"renderMessageOn",array(),
													$this,"setMessage",array($message)).
				$html->br();
	}
	
	
	public function renderContentOn($html){
		$links = "";
		foreach($this->messages as $message){
			$links .= $this->renderMessageLinkOn($html,$message);
		}
		return $this->renderMessageOn($html).
				$html->br().
				$links.
				$html->br().
				$this->renderNavigationOn($html);
	}
	
	public function renderNavigationOn($html){
		return $html->render($this->navigation);
	}
	
	public function children (){
		return array($this->navigation);
	}
	
}