<script lang="ts">
  import { createEventDispatcher } from 'svelte';

  export let book: {
    id: number;
    title: string;
    borrow_date: string;
    due_date: string;
  };

  const dispatch = createEventDispatcher();

  function handleReturn() {
    dispatch('return', book);
  }

  $: daysOverdue = Math.max(0, Math.floor((new Date().getTime() - new Date(book.due_date).getTime()) / (1000 * 60 * 60 * 24)));
  $: fine = daysOverdue * 0.5; // $0.50 per day late
</script>

<div class="bg-white shadow-md rounded-lg p-4 mb-4">
  <h2 class="text-xl font-semibold mb-2">Return: {book.title}</h2>
  <p class="mb-2">Borrow Date: {book.borrow_date}</p>
  <p class="mb-2">Due Date: {book.due_date}</p>
  <p class="mb-2">Days Overdue: {daysOverdue}</p>
  <p class="mb-4">Fine: ${fine.toFixed(2)}</p>
  <button on:click={handleReturn} class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
    Return Book
  </button>
</div>

