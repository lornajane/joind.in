<?php

	require_once 'ApiTestBase.php';
	 
	class TalkGetDetail extends ApiTestBase {

		public function testGetDetailJSON() {
			$talks_response = self::makeApiRequest('talk', 'getdetail', array('talk_id'=>2337), 'json');
			$talks = $this->decode_response($talks_response, 'json');

			$this->assertExpectedTalkFields($talks);

		}
		
		public function testGetDetailXML() {
			$talks_response = self::makeApiRequest('talk', 'getdetail', array('talk_id'=>2337), 'xml');
			$talks = $this->decode_response($talks_response, 'xml');

			$this->assertExpectedTalkFields($talks);
		}

		public function assertExpectedTalkDetailFields($talks) {
            // some additional fields are in this response
            foreach($talks as $talk) {
                $this->assertTrue(is_numeric((string)$talk->tid), 'tid must be numeric (' . $talk->ID .')');
                $this->assertEquals($talk->ID, $talk->tid, 'ID must equal tid (' . $talk->ID .')');
                $this->assertTrue(is_numeric((string)$talk->eid), 'eid must be numeric (' . $talk->ID .')');
                $this->assertEquals($talk->event_id, $talk->eid, 'event_id must equal eid (' . $talk->ID . ')');
                $this->assertTrue(is_numeric((string)$talk->lang), 'lang must be numeric (' . $talk->ID .')');
                $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING, $talk->lang_name, 'lang_name must be a string (' . $talk->ID . ')');
                $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING, $talk->lang_abbr, 'lang_abbr must be a string (' . $talk->ID . ')');
                $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING, $talk->event_name, 'event_name must be a string (' . $talk->ID . ')');
                $this->assertTrue(is_numeric((string)$talk->tavg) || empty($talk->tavg), 'tavg must be numeric or empty (' . $talk->ID .')');
                $this->assertTrue(is_numeric((string)$talk->active), 'active must be numeric (' . $talk->ID .')');
                $this->assertTrue($talk->private == 'y' ||
                        $talk->private == 0 ||
                        $talk->private == 'N',
                        'private expected to be y or N or zero (' . $talk->ID .')');
                $this->assertTrue((empty($talk->last_comment_date) || is_numeric((string)$talk->last_comment_date)), 'last_comment_date must be numeric (' . $talk->ID .')');
                $this->assertTrue(is_numeric((string)$talk->allow_comments), 'allow_comments must be numeric (' . $talk->ID .')');
            }

		}
		
	}
