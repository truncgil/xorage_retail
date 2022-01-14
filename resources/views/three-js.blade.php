<?php if(!getisset("file")) {
	echo "file parametresi gereklidir";
	exit();
} ?>
<!DOCTYPE html>
<html lang="tr"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>{{e2("The Assembler Model Görüntüleyici")}}</title>
		
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<link type="text/css" rel="stylesheet" href="assets/threejs/main.css">
	</head>

	<body>
    <div id="info">
      <img src="logo.svg" width="256" alt=""> <br>
	  {{get("file")}} <br>
	  Dosyasını görüntülüyorsunuz. 
    </div>
		<script type="module">

			import * as THREE from 'https://threejs.org/build/three.module.js'; 

			import Stats from 'https://threejs.org/examples/jsm/libs/stats.module.js';

			import { OrbitControls } from 'https://threejs.org/examples/jsm/controls/OrbitControls.js';
			import { FBXLoader } from 'https://threejs.org/examples/jsm/loaders/FBXLoader.js';

			let camera, scene, renderer, stats;

			const clock = new THREE.Clock();

			let mixer;

			init();
			animate();

			function init() {

				const container = document.createElement( 'div' );
				document.body.appendChild( container );

				camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 1, 2000 );
				camera.position.set( 100, 200, 300 );

				scene = new THREE.Scene();
				scene.background = new THREE.Color( 0xa0a0a0 );
			//	scene.fog = new THREE.Fog( 0xa0a0a0, 200, 1000 );

				const hemiLight = new THREE.HemisphereLight( 0xffffff, 0x444444 );
				hemiLight.position.set( 0, 200, 0 );
				scene.add( hemiLight );

				const dirLight = new THREE.DirectionalLight( 0xffffff );
				dirLight.position.set( 0, 200, 100 );
				dirLight.castShadow = true;
				dirLight.shadow.camera.top = 180;
				dirLight.shadow.camera.bottom = - 100;
				dirLight.shadow.camera.left = - 120;
				dirLight.shadow.camera.right = 120;
				scene.add( dirLight );

				// scene.add( new THREE.CameraHelper( dirLight.shadow.camera ) );

				// ground
				const mesh = new THREE.Mesh( new THREE.PlaneGeometry( 1000, 1000 ), new THREE.MeshPhongMaterial( { color: 0x999999, depthWrite: false } ) );
				mesh.rotation.x = - Math.PI / 2;
				mesh.receiveShadow = true; 
				scene.add( mesh );

				const grid = new THREE.GridHelper( 1000, 200, 0x000000, 0x000000 );
				grid.material.opacity = 0.2;
				grid.material.transparent = true;
				scene.add( grid );

				// model
				const loader = new FBXLoader();
				loader.load( '{{get("file")}}', function ( object ) {

				//	mixer = new THREE.AnimationMixer( object );
/*
					const action = mixer.clipAction( object.animations[ 0 ] );
					action.play();

          */
					object.traverse( function ( child ) {

						if ( child.isMesh ) {

							child.castShadow = false;
							child.receiveShadow = false;

						}

					} );
          object.scale.multiplyScalar(0.1);
					scene.add( object );

				} );

				renderer = new THREE.WebGLRenderer( { antialias: true } );
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				renderer.shadowMap.enabled = true;
				container.appendChild( renderer.domElement );

				const controls = new OrbitControls( camera, renderer.domElement );
				controls.target.set( 0, 100, 0 );
				controls.update();

				window.addEventListener( 'resize', onWindowResize );

				// stats
				stats = new Stats();
				container.appendChild( stats.dom );

			}

			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

			}

			//

			function animate() {

				requestAnimationFrame( animate );

				const delta = clock.getDelta();

				if ( mixer ) mixer.update( delta );

				renderer.render( scene, camera );

				stats.update();

			}

		</script>

	

<div>
  <div style="position: fixed; top: 0px; left: 0px; cursor: pointer; opacity: 0.9; z-index: 10000;"><canvas width="80"
      height="48" style="width: 80px; height: 48px; display: none;"></canvas><canvas width="80" height="48"
      style="width: 80px; height: 48px; display: block;"></canvas><canvas width="80" height="48"
      style="width: 80px; height: 48px; display: none;"></canvas></div>
</div>
</body>

</html>