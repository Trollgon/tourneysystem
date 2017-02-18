<?php
/**
 * Created by Trollgon.
 * Date: 14.02.2017
 * Time: 10:54
 */

namespace tourneysystem\page;


use tourneysystem\data\tourney\Tourney;
use wcf\page\AbstractPage;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\WCF;

class ControlPanelPage extends AbstractPage {

    public $tourney;
    public $tourneyID = 0;

    /**
     * @see \wcf\page\AbstractPage::readParameters()
     */
    public function readParameters() {
        parent::readParameters();
        if(isset($_REQUEST['tourneyID']))
            $this->tourneyID = intval($_REQUEST['tourneyID']);
        if($this->tourneyID == 0) {
            throw new IllegalLinkException();
        }
        $this->tourney = new Tourney($this->tourneyID);

        $this->refereeList = $this->tourney->getReferees();

        if($this->tourney->tourneyID == null || $this->tourney->tourneyID == 0) {
            throw new IllegalLinkException();
        }
    }

    /**
     * @see \wcf\page\AbstractPage::show()
     */
    public function show() {
        if (!$this->tourney->isReferee(WCF::getUser()->getUserID())) {
            throw new PermissionDeniedException();
        }

        parent::show();
    }

    /**
     * @see \wcf\page\AbstractPage::assignVariables()
     */
    public function assignVariables() {
        parent::assignVariables();

        $signUp = $this->tourney->getSignUp();

        WCF::getTPL()->assign(array(
            'juryArray' =>  $this->tourney->getReferees(),
            'signUp'    =>  $signUp,
            'tourney'   =>  $this->tourney,
        ));
    }
}