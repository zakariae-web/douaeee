<template>
  <transition name="slide-fade">
    <div v-if="show" class="progression-notification" :class="{ 'level-up': isLevelUp }">
      <div class="notification-content">
        <div class="notification-icon">
          <i :class="isLevelUp ? 'fas fa-star' : 'fas fa-plus-circle'"></i>
        </div>
        <div class="notification-text">
          <h3 v-if="isLevelUp">Niveau {{ progression.new_level }} atteint !</h3>
          <p v-else>+{{ progression.xp_gained }} XP</p>
          <p v-if="isLevelUp" class="level-up-text">
            Félicitations ! Vous avez atteint le niveau {{ progression.new_level }}
          </p>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'ProgressionNotification',
  props: {
    progression: {
      type: Object,
      required: true
    },
    show: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    isLevelUp() {
      return this.progression.leveled_up;
    }
  }
}
</script>

<style scoped>
.progression-notification {
  position: fixed;
  top: 20px;
  right: 20px;
  background: white;
  border-radius: 12px;
  padding: 1rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  z-index: 1000;
  min-width: 300px;
}

.progression-notification.level-up {
  background: #4CAF50;
  color: white;
}

.notification-content {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.notification-icon {
  font-size: 2rem;
}

.notification-text {
  flex: 1;
}

.notification-text h3 {
  margin: 0;
  font-size: 1.2rem;
  font-weight: bold;
}

.notification-text p {
  margin: 0.25rem 0 0;
  font-size: 0.9rem;
}

.level-up-text {
  opacity: 0.9;
}

/* Animations */
.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.3s ease-in;
}

.slide-fade-enter-from {
  transform: translateX(30px);
  opacity: 0;
}

.slide-fade-leave-to {
  transform: translateX(30px);
  opacity: 0;
}
</style> 