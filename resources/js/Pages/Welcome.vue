<template>
    <div>
        <canvas ref="canvas" width="1024" height="1920" style="border: 1px solid #000;"></canvas>
    </div>
  </template>
  
<script setup>
  import { onMounted, onUnmounted, ref, reactive } from 'vue';
  import { router } from '@inertiajs/vue3';

  const homeUrl = window.location

  const canvas = ref(null);
  const scale = 0.04; // Adjust based on your coordinate system
  const lastPositions = reactive({});
  const historyLines = reactive({}); // Store line history

  const mapscalex = ref(1)
  const mapscaley = ref(1)

  const requests = ref(0)

  const zoom = () => {
      scale.value = scale.value * 2
      console.log(mapscalex.value);
  }

  const idToColor = (id) => {
    let hash = 0;
    for (let i = 0; i < id.length; i++) {
      hash = id.charCodeAt(i) + ((hash << 5) - hash);
    }
    return `hsl(${hash % 360}, 100%, 50%)`;
  };

  const drawDotsAndLines = (ctx, data) => {
    ctx.clearRect(0, 0, canvas.value.width, canvas.value.height); // Clear the canvas
    
    const centerX = canvas.value.width / 3;
    const centerY = canvas.value.height / 3;

    // First, redraw all historical lines
    Object.values(historyLines).forEach(lines => {
      lines.forEach(({from, to, color}) => {
        ctx.beginPath();
        ctx.moveTo(from.x, from.y);
        ctx.lineTo(to.x, to.y);
        ctx.strokeStyle = color;
        ctx.stroke();
      });
    });

    // Process new data
    data.forEach((element, index) => {

      Object.entries(JSON.parse(element.pilotPositions)).forEach(([id, { X, Y }]) => {
        const color = idToColor(id);
        const scaledX = centerX + X * scale;
        const scaledY = centerY + Y * scale;

        // Add new line to history if previous position exists
        if (lastPositions[id]) {
          if (!historyLines[id]) historyLines[id] = [];
          historyLines[id].push({
            from: {x: lastPositions[id].x, y: lastPositions[id].y},
            to: {x: scaledX, y: scaledY},
            color
          });
        }

        // Update last positions with current for next update
        lastPositions[id] = { x: scaledX, y: scaledY };
      });
    });
    var newdata = JSON.parse(data[0].pilotPositions)
    console.warn(newdata)
    // After processing all lines, draw the latest dot and ID for each element
    Object.entries(JSON.parse(data[0].pilotPositions)).forEach(([id, { X, Y }]) => { // Assumes the latest data is the last entry
      const scaledX = centerX + X * scale;
      const scaledY = centerY + Y * scale;
      const color = idToColor(id);

      // Draw the dot
      ctx.beginPath();
      ctx.arc(scaledX, scaledY, 5, 0, 2 * Math.PI);
      ctx.fillStyle = color;
      ctx.fill();
      ctx.scale(mapscalex,mapscaley)
      // Draw the ID
      ctx.font = '12px Arial';
      ctx.fillStyle = 'black';
      ctx.fillText(id, scaledX + 10, scaledY + 3);
    });
  };

  const fetchCoordinates = async () => {
    try {
      const response = await fetch(`${homeUrl}api/coordinates`);
      if (!response.ok) throw new Error('Network response was not ok');
      const data = await response.json();
      const ctx = canvas.value.getContext('2d');
      drawDotsAndLines(ctx,data);
      requests.value = data
    } catch (error) {
      console.error('Error fetching coordinates:', error);
    }
  };

  let intervalId;

  onMounted(() => {
    fetchCoordinates();
    intervalId = setInterval(fetchCoordinates, 1500); // Refresh every second
    
  });

  onUnmounted(() => {
    clearInterval(intervalId); // Cleanup on component unmount
  });
</script>
