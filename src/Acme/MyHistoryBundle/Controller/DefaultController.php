<?php


namespace Acme\MyHistoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\MyHistoryBundle\Entity\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
	public function addAction(Request $request)
	{
		$event = new Event();	
	
		$form = $this->createFormBuilder($event)
		->add('title', 'text',array('label'  => 'EVENT TITLE'))
		->add('link', 'url', array('label'  => 'URL'))
		->add('start', 'date', array('label'  => 'START',
									'widget' => 'single_text', 
									'input' => 'datetime', 
									'format' => 'dd/MM/yyyy', 
									'attr' => array('class' => 'datepicker'),
                                     )) 	 
		->add('end', 'date', array('label'  => 'END',
									'widget' => 'single_text', 
									'input' => 'datetime', 
									'format' => 'dd/MM/yyyy', 
									'attr' => array('class' => 'datepicker'),
                                     )) 
		->add('description', 'textarea', array('label'  => 'DESCRIPTION'))			 
		->add('durationEvent', 'checkbox', array('required'  => false, 'label'  => 'IS-IT A DURATION EVENT ? ' ))	
		->add('address', 'text', array('label'  => 'ADDRESS'))
		->add('lat', 'text', array('label'  => 'LATITUDE'))
		->add('lng', 'text', array('label'  => 'LONGITUDE'))
		->getForm();
		
		if ($request->getMethod() == 'POST') 
		{
			$form->bindRequest($request);
			if ($form->isValid()) 
			{
				// perform some action, such as saving the task to the database
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($event);
				$em->flush();
				//return new Response('<html><body>Enregistrement ok, n� ' . $event->getId(). '</body></html>');
				return $this->redirect($this->generateUrl('event_timeline'));
				//return $this->showallAction();
			}
		}
		return $this->render('AcmeMyHistoryBundle:Default:new2.html.twig', array(
		'form' => $form->createView(),
		));
	} 
	

	
	public function showAction($id)
	{
		$event = $this->getDoctrine()
		->getRepository('AcmeMyHistory:Event')
		->find($id);
		if (!$event) {
		throw $this->createNotFoundException('No event found for id '.$id);
		}
		return $this->render('AcmeMyHistory:Default:all.html.twig',
		array('event' => $event));
	}
	
	public function showallAction()
	{
		$event = $this->getDoctrine()
		->getRepository('AcmeMyHistoryBundle:Event')
		->findAll();
		
		
		if (!$event) {
		throw $this->createNotFoundException('No event found for');
		}
		return $this->render('AcmeMyHistoryBundle:Default:all.html.twig',
		array('event' => $event));
	}
	
	public function timelineAction()
	{
		 $event = $this->getDoctrine()
		->getRepository('AcmeMyHistoryBundle:Event')
		->findAll();
		
		
		if (!$event) {
		throw $this->createNotFoundException('No event found for');
		}
		
		// ecriture du fichier local_data.js
		$fp = fopen ("local_data.js", "w+");		
		fputs($fp, "var timeline_data = { \n 'dateTimeFormat': 'iso8601', \n 'wikiURL': \"http://simile.mit.edu/shelf/\", \n 'wikiSection': \"Simile Cubism Timeline\", \n 'events' : \n[");
		foreach ($event as $item)
		{
			$start = $item->getStart()->format('c');
			$end = $item->getEnd()->format('c');
			$title = str_replace("'","&#039;",$item->getTitle());
			$desc = str_replace("'","&#039;",$item->getDescription());
			$link = $item->getLink();
			$duration = "false";
			if ($item->getDurationEvent()) $duration = "true";
			
			
			fputs($fp,"\n\n\t{'start' : '" . $start . "',");
			fputs($fp,"\n\t'end' : '" . $end . "',");
			fputs($fp,"\n\t'title' : '" . $title . "',");
			fputs($fp,"\n\t'description' : '" . $desc . "',");
			fputs($fp,"\n\t'link' : '" . $link . "',");			
			fputs($fp,"\n\t'durationEvent' : " . $duration . "},");

		};
		
		fputs($fp, "\n]\n}" );
		fclose($fp);

		
		// chargement de la page widget (local_example.html)
		
		//return $this->render('AcmeMyHistoryBundle:Default:local_data.js.twig',
		//array('event' => $event));
		
		return $this->redirect("http://localhost/Symfony/web/local_example.html");
		
	}
	
	
	
}
