/**
 * Menghitung rata-rata rating dari sebuah array nilai rating.
 * Menangani kasus array kosong untuk menghindari Division by Zero.
 *
 * @param {number[]} ratings - Array berisi nilai-nilai rating (integer 1-5)
 * @returns {number} Rata-rata rating dibulatkan 1 desimal, atau 0 jika array kosong
 */
function calculateAverageRating(ratings) {
  if (!Array.isArray(ratings) || ratings.length === 0) {
    return 0;
  }

  const sum = ratings.reduce((acc, val) => acc + val, 0);
  const average = sum / ratings.length;

  return parseFloat(average.toFixed(1));
}

module.exports = calculateAverageRating;
