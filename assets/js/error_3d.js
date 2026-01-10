import * as THREE from 'https://unpkg.com/three@0.160.0/build/three.module.js';
import { GLTFLoader } from 'https://unpkg.com/three@0.160.0/examples/jsm/loaders/GLTFLoader.js';
import { DRACOLoader } from 'https://unpkg.com/three@0.160.0/examples/jsm/loaders/DRACOLoader.js';

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('error-3d-scene');
    if (!container) return;

    // 1. Scene Setup
    const scene = new THREE.Scene();

    // 2. Camera Setup
    const width = container.clientWidth;
    const height = container.clientHeight;
    // FOV, Aspect, Near, Far
    const camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
    camera.position.z = 6;

    // 3. Renderer Setup
    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setSize(width, height);
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    container.appendChild(renderer.domElement);

    // 4. Lighting
    const ambientLight = new THREE.AmbientLight(0xffffff, 2);
    scene.add(ambientLight);

    const dirLight = new THREE.DirectionalLight(0xffffff, 3);
    dirLight.position.set(5, 5, 5);
    scene.add(dirLight);

    const redLight = new THREE.PointLight(0xff4757, 2);
    redLight.position.set(-5, -5, 5);
    scene.add(redLight);

    // 5. Load GLB Model (with DRACO support)
    const dracoLoader = new DRACOLoader();
    dracoLoader.setDecoderPath('https://unpkg.com/three@0.160.0/examples/jsm/libs/draco/');

    const loader = new GLTFLoader();
    loader.setDRACOLoader(dracoLoader);

    let model;

    // Use Path from PHP
    const modelPath = window.AIM_LOGO_URL || 'assets/logo/aim-3d.glb';

    loader.load(
        modelPath,
        (gltf) => {
            model = gltf.scene;

            // Center geometry
            const box = new THREE.Box3().setFromObject(model);
            const center = box.getCenter(new THREE.Vector3());
            const size = box.getSize(new THREE.Vector3());

            // Re-center
            model.position.x += (model.position.x - center.x);
            model.position.y += (model.position.y - center.y);
            model.position.z += (model.position.z - center.z);

            // Scale
            const maxDim = Math.max(size.x, size.y, size.z);
            const scale = 3.5 / maxDim;
            model.scale.set(scale, scale, scale);

            scene.add(model);
        },
        undefined,
        (error) => {
            console.error('An error happened loading the GLB:', error);
            // Fallback: Show a red box with ? texture or just a red box
            const geometry = new THREE.BoxGeometry(2, 2, 2);
            const material = new THREE.MeshStandardMaterial({ color: 0xff0000 });
            model = new THREE.Mesh(geometry, material);
            scene.add(model); // Add cube so user sees SOMETHING
        }
    );

    // 6. Animation
    function animate() {
        requestAnimationFrame(animate);

        if (model) {
            // Spin
            model.rotation.y += 0.01;
            model.rotation.x = Math.sin(Date.now() * 0.001) * 0.2; // Tilt
            // Bob up and down
            model.position.y = Math.sin(Date.now() * 0.002) * 0.2;
        }

        renderer.render(scene, camera);
    }

    animate();

    // 7. Resize Handler
    window.addEventListener('resize', () => {
        if (!container) return;
        const newWidth = container.clientWidth;
        const newHeight = container.clientHeight;

        camera.aspect = newWidth / newHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(newWidth, newHeight);
    });
});
