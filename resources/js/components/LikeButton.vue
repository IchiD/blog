<template>
  <div>
    <button @click="toggleLike" class="btn btn-success mb-3">
      <span class="like-text">{{ liked ? 'いいね!!を取り消す' : 'いいね!!' }}</span>
      <span class="like-count badge badge-light ">{{ likeCount }}</span>
    </button>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: ['topicId', 'initialLiked', 'initialCount'],
  data() {
    return {
      liked: this.initialLiked,
      likeCount: this.initialCount
    };
  },
  methods: {
    async toggleLike() {
      try {
        const response = await axios.post(`/topic/like/${this.topicId}`, null, {
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        });
        this.liked = response.data.liked;
        this.liked ? this.likeCount++ : this.likeCount--;
      } catch (error) {
        console.error("Error:", error);
        alert("いいねの処理に失敗しました。");
      }
    }
  }
}
</script>
