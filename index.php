<!doctype html> 
<html lang="en"> 
<head> 
<link rel="shortcut icon" href="assets/chetos.png">
    <meta charset="UTF-8" />
    <title>Chetojuego</title>
    <script src="//cdn.jsdelivr.net/npm/phaser@3.11.0/dist/phaser.js"></script>
    <style type="text/css">
        body {
            margin: 0;
        }
    </style>
</head>
<body>

<script type="text/javascript">

var config = {
    type: Phaser.AUTO,
    width: 800,
    height: 600,
    physics: {
        default: 'arcade',
        arcade: {
            gravity: { y: 300 },
            debug: false
        }
    },
    scene: {
        preload: preload,
        create: create,
        update: update
    }
};

var player;
var stars;
var bombs;
var platforms;
var cursors;
var score = 0;
var gameOver = false;
var scoreText;

var game = new Phaser.Game(config);

function preload ()
{
    this.load.image('fondo', 'assets/fondo.png');
    this.load.image('ground', 'assets/platform.png');
    this.load.image('chetos', 'assets/chetos.png');
    this.load.image('gameover', 'assets/gameover.png');
    this.load.spritesheet('dude', 'assets/dude.png', { frameWidth: 32, frameHeight: 32 });
    this.load.spritesheet('dude2', 'assets/dude2.png', { frameWidth: 32, frameHeight: 32 });
    this.load.spritesheet('cierra', 'assets/cierra.png', { frameWidth: 38, frameHeight: 38 });
   
}


function create ()
{
    
    this.add.image(400, 300, 'fondo');

  
    platforms = this.physics.add.staticGroup();


    platforms.create(400, 568, 'ground').setScale(2).refreshBody();

   
    platforms.create(600, 400, 'ground');
    platforms.create(50, 250, 'ground');
    platforms.create(750, 220, 'ground');

    player = this.physics.add.sprite(100, 450, 'dude');

    player.setBounce(0.10);
    player.setCollideWorldBounds(true);


    this.anims.create({
        key: 'left',
        frames: this.anims.generateFrameNumbers('dude2', { start: 0, end: 3 }),
        frameRate: 10,
        repeat: -1
    });

    this.anims.create({
        key: 'turn',
        frames: [ { key: 'dude', frame: 4 } ],
        frameRate: 20
    });

    this.anims.create({
        key: 'right',
        frames: this.anims.generateFrameNumbers('dude', { start: 5, end: 8 }),
        frameRate: 10,
        repeat: -1
    });
    this.anims.create({
        key: 'cierra',
        frames: this.anims.generateFrameNumbers('cierra', { start: 0, end: 3 }),
        frameRate: 10,
        repeat: -1
    });
  

    
    cursors = this.input.keyboard.createCursorKeys();

    stars = this.physics.add.group({
        key: 'chetos',
        repeat: 11,
        setXY: { x: 12, y: 0, stepX: 70 }
    });

    stars.children.iterate(function (child) {

       
        child.setBounceY(Phaser.Math.FloatBetween(0.4, 0.8));

    });

    bombs = this.physics.add.group();

   
    scoreText = this.add.text(16, 16, 'Chetospuntos: 0', { fontSize: '32px', fill: '#001' });

   
    this.physics.add.collider(player, platforms);
    this.physics.add.collider(stars, platforms);
    this.physics.add.collider(bombs, platforms);

    this.physics.add.overlap(player, stars, collectStar, null, this);

    this.physics.add.collider(player, bombs, hitBomb, null, this);
}

function update ()
{
    if (gameOver)
    {
        return;
    }

    if (cursors.left.isDown)
    {
        player.setVelocityX(-160);

        player.anims.play('left', true);
    }
    else if (cursors.right.isDown)
    {
        player.setVelocityX(160);

        player.anims.play('right', true);
    }
    else
    {
        player.setVelocityX(0);

        player.anims.play('turn');
    }

    if (cursors.space.isDown && player.body.touching.down)
    {
        player.setVelocityY(-310);
    }
}

function collectStar (player, chetos)
{
    chetos.disableBody(true, true);

    score += 1;
    scoreText.setText('Chetospuntos: ' + score);

    if (stars.countActive(true) === 0)
    {
        
        stars.children.iterate(function (child) {

            child.enableBody(true, child.x, 0, true, true);

        });

        var x = (player.x < 400) ? Phaser.Math.Between(400, 800) : Phaser.Math.Between(0, 400);

        var cierra = bombs.create(x, 16, 'cierra');
        cierra.setBounce(1);
        cierra.setCollideWorldBounds(true);
        cierra.setVelocity(Phaser.Math.Between(-200, 200), 20);
        cierra.allowGravity = false;

    }
}

function hitBomb (player, cierra)
{
    this.physics.pause();

    player.setTint(0xff0000);

    player.anims.play('turn');
    
    gameOver = true;
    gameoverImage =this.add.image(400, 90,'gameover');
    
}

</script>

</body>
</html>