<template>
  <div class="user-progression">
    <div class="level-info">
      <div class="level-badge">
        <span class="level-number">{{ progression.current_level }}</span>
        <span class="level-label">Niveau</span>
      </div>
      <div class="xp-info">
        <div class="xp-text">
          <span class="current-xp">{{ progression.current_xp }}</span>
          <span class="xp-separator">/</span>
          <span class="next-level-xp">{{ progression.xp_for_next_level }}</span>
          <span class="xp-label">XP</span>
        </div>
        <div class="progress-bar">
          <div 
            class="progress-fill" 
            :style="{ width: `${progression.progress_percentage}%` }"
          ></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserProgression',
  props: {
    progression: {
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped>
.user-progression {
  background: white;
  border-radius: 12px;
  padding: 1rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.level-info {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.level-badge {
  background: #4CAF50;
  color: white;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.level-number {
  font-size: 1.5rem;
  font-weight: bold;
}

.level-label {
  font-size: 0.8rem;
  text-transform: uppercase;
}

.xp-info {
  flex: 1;
}

.xp-text {
  display: flex;
  align-items: baseline;
  gap: 0.25rem;
  margin-bottom: 0.5rem;
}

.current-xp {
  font-size: 1.2rem;
  font-weight: bold;
  color: #4CAF50;
}

.xp-separator {
  color: #666;
}

.next-level-xp {
  color: #666;
}

.xp-label {
  font-size: 0.8rem;
  color: #666;
  margin-left: 0.25rem;
}

.progress-bar {
  height: 8px;
  background: #eee;
  border-radius: 4px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: #4CAF50;
  transition: width 0.3s ease;
}
</style> 