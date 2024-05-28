<?php

namespace ThSplitLib;

use Aosy\ThSplitLib\Segment;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class SegmentTest extends TestCase
{
    private $segment;

    protected function setUp(): void
    {
        parent::setUp();
        $this->segment = new Segment();
    }

    public function testGet_segment_array()
    {
        $this->segment->setLocal('th_TH');
        $input = 'คาร์โก้กฤษณ์คอมเมนต์เฮอร์ริเคนซีดาน';
        $result = $this->segment->get_segment_array($input);
        $output = [
            'คาร์',
            'โก้',
            'กฤษณ์',
            'คอม',
            'เมนต์',
            'เฮอร์',
            'ริ',
            'เคน',
            'ซี',
            'ดาน',
            ' ',
        ];
        foreach ($output as $item) {
            $this->assertContains($item, $result);
        }
    }

    public function testGetLocal()
    {
        $result = $this->segment->getLocal();
        $this->assertEquals('th_TH', $result);
    }

    public function testSetLocal()
    {
        $this->segment->setLocal('en_US');
        $this->assertEquals('en_US', $this->segment->getLocal());
    }

    public function testWord()
    {
        $input = 'คาร์โก้กฤษณ์คอมเมนต์เฮอร์ริเคนซีดาน';
        $result = Segment::word($input);
        $this->assertEquals('คาร์|โก้|กฤษณ์|คอม|เมนต์|เฮอร์|ริ|เคน|ซี|ดาน| ', $result);
    }

}
