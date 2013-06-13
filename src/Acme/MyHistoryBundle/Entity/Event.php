<?php

namespace Acme\MyHistoryBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="event")
*/
class Event
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;
	
	/**
	* @ORM\Column(type="string", length=100)
	*/
	protected $title;
	
	/**
	* @ORM\Column(type="text")
	*/
	protected $link;
	
	/**
	* @ORM\Column(type="datetime")
	*/
	protected $start;
	
	/**
	* @ORM\Column(type="datetime")
	*/
	protected $latestStart;
	
	/**
	* @ORM\Column(type="datetime")
	*/
	protected $earliestEnd;
	
	/**
	* @ORM\Column(type="datetime")
	*/
	protected $end;
	
	/**
	* @ORM\Column(type="boolean")
	*/
	protected $durationEvent;

	/**
	* @ORM\Column(type="boolean")
	*/
	protected $flag = false;
	
	/**
	* @ORM\Column(type="text")
	*/
	protected $address;
	
	/**
	* @ORM\Column(type="text")
	*/
	protected $lat;
	
	/**
	* @ORM\Column(type="text")
	*/
	protected $lng;
	
		/**
	* @ORM\Column(type="text")
	*/
	protected $description;
	
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param text $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return text 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set start
     *
     * @param date $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * Get start
     *
     * @return date 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set latestStart
     *
     * @param date $latestStart
     */
    public function setLatestStart($latestStart)
    {
        $this->latestStart = $latestStart;
    }

    /**
     * Get latestStart
     *
     * @return date 
     */
    public function getLatestStart()
    {
        return $this->latestStart;
    }

    /**
     * Set earliestEnd
     *
     * @param date $earliestEnd
     */
    public function setEarliestEnd($earliestEnd)
    {
        $this->earliestEnd = $earliestEnd;
    }

    /**
     * Get earliestEnd
     *
     * @return date 
     */
    public function getEarliestEnd()
    {
        return $this->earliestEnd;
    }

    /**
     * Set end
     *
     * @param date $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * Get end
     *
     * @return date 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set durationEvent
     *
     * @param boolean $durationEvent
     */
    public function setDurationEvent($durationEvent)
    {
        $this->durationEvent = $durationEvent;
    }

    /**
     * Get durationEvent
     *
     * @return boolean 
     */
    public function getDurationEvent()
    {
        return $this->durationEvent;
    }

    /**
     * Set link
     *
     * @param text $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Get link
     *
     * @return text 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set flag
     *
     * @param boolean $flag
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

    /**
     * Get flag
     *
     * @return boolean 
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Set adress
     *
     * @param text $adress
     */
    public function setAddress($adress)
    {
        $this->address = $adress;
    }

    /**
     * Get adress
     *
     * @return text 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set lat
     *
     * @param text $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * Get lat
     *
     * @return text 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param text $lng
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    }

    /**
     * Get lng
     *
     * @return text 
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set description
     *
     * @param textarea $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return textarea 
     */
    public function getDescription()
    {
        return $this->description;
    }
}
