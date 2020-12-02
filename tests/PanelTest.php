<?php

use PHPUnit\Framework\TestCase;

class PanelTest extends TestCase
{
    protected $panel;

    public function setUp(): void
    {
        WP_Mock::setUp();
        $this->panel = new CF7_Toolkit\Panels\Panel();
    }

    public function tearDown(): void
    {
        unset( $this->panel, $_POST );
        WP_Mock::tearDown();
    }

    public function testHasDefaultMeta(): void
    {
        $this->assertEquals( 'wpcf7_toolkit_panel', $this->panel->get( 'name' ) );
    }

    public function testAddsCoreHooks(): void
    {
        WP_Mock::expectActionAdded( 'wpcf7_editor_panels', [ $this->panel, 'register' ] );
        WP_Mock::expectActionAdded( 'wpcf7_after_save', [ $this->panel, 'save' ] );
        $this->panel->init();

        WP_Mock::assertHooksAdded();
    }

    public function testRegistersItself(): void
    {
        $actual = $this->panel->register([]);

        $this->assertArrayHasKey( 'wpcf7_toolkit_panel', $actual );
    }

    public function testRenders(): void
    {
        $form = Mockery::mock('WPCF7_ContactForm')->makePartial();
        $this->panel->render( $form );

        $this->expectOutputString( '<h2>Panel</h2>' );
    }

    public function testSaveSucceedsWithData(): void
    {
        $_POST = [ 'wpcf7_toolkit_panel' => [] ];

        $form = Mockery::mock( 'WPCF7_ContactForm' )->makePartial();
        $form->shouldReceive( 'id' )->andReturn( 1 );

        WP_Mock::userFunction( 'update_option', [
            'return' => TRUE,
            'times'  => 1,
        ] );

        $actual = $this->panel->save($form);

        $this->assertEquals( TRUE, $actual );
    }

    public function testSaveFailsWithoutData(): void
    {
        $form = Mockery::mock('WPCF7_ContactForm')->makePartial();
        $form->shouldReceive('id')->andReturn(1);

        $actual = $this->panel->save($form);

        $this->assertEquals( FALSE, $actual );
    }
}
