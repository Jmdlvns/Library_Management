import { writable } from 'svelte/store';

export interface Borrowing {
  id: number;
  bookId: number;
  title: string;
  author: string;
  borrowDate: string;
}

function createBorrowStore() {
  const { subscribe, set, update } = writable<Borrowing[]>([]);

  return {
    subscribe,
    borrowBook: (borrowing: Borrowing) => update(borrowings => [...borrowings, borrowing]),
    returnBook: (id: number) => update(borrowings => borrowings.filter(borrowing => borrowing.id !== id)),
    setBorrowings: (borrowings: Borrowing[]) => set(borrowings),
  };
}

export const borrowStore = createBorrowStore();

