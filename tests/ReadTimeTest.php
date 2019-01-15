<?php

use PHPUnit\Framework\TestCase;

use Mtownsend\ReadTime\ReadTime;

class ReadTimeTest extends TestCase
{

    /** @test string */
    protected $mainContent;

    /** @test string */
    protected $sidebarContent;

    protected function setUp()
    {
    	$this->mainContent = <<<HTML
    	<h2>Chicken Enchiladas</h2>
    	<small>By Leroy Jenkins</small>
    	<p>
    		Lorem ipsum dolor sit amet, in sint erat intellegebat sit, accumsan maluisset mea ei. At wisi omnis usu, has in tibique singulis temporibus. Mel ne aeque oblique, per id probatus imperdiet. Per tale legimus facilisis ex.
    	</p>
    	<p>
    		Sonet dolor honestatis mel ne. Agam salutandi ut ius. Liber euismod vivendo id est, vim eu putent adipisci salutandi. Graeco habemus vix at, in eam suas habeo. Eu mel denique disputationi, ius ut idque repudiandae, his ex habeo petentium definiebas.
    	</p>
    	<ol>
    		<li>
    			Coat large saute pan with oil. Season chicken with salt and pepper. Brown chicken over medium heat, allow 7 minutes each side or until no longer pink. Sprinkle chicken with cumin, garlic powder and Mexican spices before turning. Remove chicken to a platter, allow to cool.
    		</li>
    		<li>
    			Saute onion and garlic in chicken drippings until tender. Add corn and chiles. Stir well to combine. Add canned tomatoes, saute 1 minute.
    		</li>
    		<li>
    			Pull chicken breasts apart by hand into shredded strips. Add shredded chicken to saute pan, combine with vegetables. Dust the mixture with flour to help set.
    		</li>
    		<li>
    			Microwave tortillas on high for 30 seconds. This softens them and makes them more pliable. Coat the bottom of 2 (13 by 9-inch) pans with a ladle of enchilada sauce. Using a large shallow bowl, dip each tortilla in enchilada sauce to lightly coat. Spoon 1/4 cup chicken mixture in each tortilla. Fold over filling, place 8 enchiladas in each pan with seam side down. Top with remaining enchilada sauce and cheese.
    		</li>
    		<li>
    			Bake for 15 minutes in a preheated 350 degree F oven until cheese melts. Garnish with cilantro, scallion, sour cream and chopped tomatoes before serving. Serve with Spanish rice and beans.
    		</li>
    	</ol>
HTML;
    	$this->sidebarContent = <<<HTML
    	<ul>
    		<li>
    			<p>
    				Level: <strong>Intermediate</strong>
    			</p>
    			<p>
    				Total: <strong>1 hr 15 min</strong>
    			</p>
    		</li>
    		<li>
    			<p>
    				Prep: <strong>1 hr</strong>
    			</p>
    			<p>
    				Cook: <strong>15 min</strong>
    			</p>
    		</li>
    		<li>
    			<p>
    				Yield: <strong>16 enchiladas, 8 servings</strong>
    			</p>
    			<p>
    				<a href="#">Nutrition Information</a>
    			</p>
    		</li>
    	</ul>
HTML;
    }

    /** @test */
    public function can_output_read_time()
    {
    	$result = (new ReadTime($this->mainContent))->get();
        $this->assertSame($result, '1 minute read');
    }

    /** @test */
    public function can_accept_array_of_content()
    {
    	$result = (new ReadTime([$this->mainContent, $this->sidebarContent]))->get();
        $this->assertSame($result, '1 minute read');
    }

    /** @test */
    public function can_change_wpm()
    {
    	$result = (new ReadTime($this->mainContent))->wpm(150)->toArray();
        $this->assertSame($result['words_per_minute'], 150);
    }

    /** @test */
    public function can_set_time_only()
    {
    	$result = (new ReadTime($this->mainContent))->timeOnly(true)->toArray();
        $this->assertTrue($result['time_only']);
    }

    /** @test */
    public function can_allow_seconds()
    {
    	$result = (new ReadTime($this->mainContent))->omitSeconds(false)->toArray();
        $this->assertFalse($result['omit_seconds']);
    }

    /** @test */
    public function can_change_translation()
    {
    	$spanish = [
		    'min' => 'min',
		    'minute' => 'minuto',
		    'sec' => 'seg',
		    'second' => 'segundo',
		    'read' => 'leer'
    	];
    	$result = (new ReadTime($this->mainContent))->setTranslation($spanish)->getTranslation('read');
        $this->assertSame($result, 'leer');
    }

    /** @test */
    public function can_get_translation_array()
    {
		$result = (new ReadTime($this->mainContent))->getTranslation();
		$this->assertInternalType('array', $result);
    }

    /** @test */
    public function can_get_translation_key()
    {
		$result = (new ReadTime($this->mainContent))->getTranslation('minute');
		$this->assertSame($result, 'minute');
    }

    /** @test */
    public function can_read_right_to_left()
    {
    	$result = (new ReadTime($this->mainContent))->omitSeconds(false)->rtl()->get();
        $this->assertSame($result, 'read second 10 minute 1');
    }

    /** @test */
    public function can_output_array()
    {
		$result = (new ReadTime($this->mainContent))->toArray();
		$this->assertInternalType('array', $result);
    }

    /** @test */
    public function can_output_json()
    {
		$result = (new ReadTime($this->mainContent))->toJson();
		$this->assertInternalType('string', $result);
    }

    /** @test */
    public function can_invoke_class_for_read_time()
    {
    	$result = new ReadTime($this->mainContent);
    	$this->assertInternalType('string', $result());
    }

    /** @test */
    public function can_cast_as_string_for_read_time()
    {
    	$result = new ReadTime($this->mainContent);
    	$this->assertInternalType('string', (string) $result);
    }

    /** @test */
    public function can_handle_large_content()
    {
        $result = (new ReadTime(str_repeat($this->mainContent, 50)))->get();
        $this->assertSame($result, '58 minute read');
    }
}
