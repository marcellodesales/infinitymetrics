<?php

require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/util/AbstractCollection.class.php';
/**
 * Description of UserTestclass
 *
 * @author Marcello de Sales (marcello.sales@gmail.com)
 */
class AbstractCollectionTest extends PHPUnit_Framework_TestCase {

    private $subject;

    protected function setUp() {
        parent::setUp();
        $this->subject = AbstractCollection::makeNewInstance();
    }

    public function testCollectionCreation() {
        $this->assertNotNull($this->subject);
        $newCollection = AbstractCollection::makeNewInstance();
        $this->assertNotSame($this->subject, $newCollection);
    }

    public function testManipulationCollection() {
        //asserting the creation is empty
        $this->assertEquals(true, $this->subject->isEmpty());
        $this->assertTrue($this->subject->size() == 0);
        //adding items
        $this->subject->add("Marcello");
        $this->subject->add("de Sales");
        //asserting the size of the collection changed and is not empty
        $this->assertTrue($this->subject->size() == 2);
        $this->assertEquals(false, $this->subject->isEmpty());
        //verifying the elements
        $this->assertTrue($this->subject->contains("Marcello"));
        $this->assertTrue($this->subject->contains("de Sales"));
    }

    public function testDifferentSizeAndEmpty() {
        //previsous values
        $previousSize = $this->subject->size();
        $this->subject->add("SFSU");
        $this->assertEquals($previousSize + 1, $this->subject->size());
        //after clearing
        $this->subject->clear();
        $this->assertTrue($this->subject->size() == 0);
        $this->assertTrue($this->subject->isEmpty());
        //after collection
    }

    public function testAddCollections() {
        $newCollection = AbstractCollection::makeNewInstance();
        $newCollection->add("FAU");
        $newCollection->add("SFSU");
        $this->subject->addAll($newCollection);
        $this->assertTrue($this->subject->size()== 2);
        //verifying
        $this->assertTrue($this->subject->contains("FAU"));
        $this->assertTrue($this->subject->contains("SFSU"));
        //adding more collections, preserving the previous
        $newCollection2 = AbstractCollection::makeNewInstance();
        $newCollection2->add("FAU2");
        $newCollection2->add("SFSU2");
        $this->subject->addAll($newCollection2);
        $this->assertTrue($this->subject->contains("FAU"));
        $this->assertTrue($this->subject->contains("SFSU"));
        $this->assertTrue($this->subject->contains("FAU2"));
        $this->assertTrue($this->subject->contains("SFSU2"));
        $this->assertEquals(4, $this->subject->size());
        $this->assertEquals(false, $this->subject->isEmpty());
    }
    
    protected function tearDown() {
        $this->subject = null;
    }
}
?>
