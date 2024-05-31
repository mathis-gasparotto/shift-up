export const letterNumbers = {}
const letterCount = 26

for (let i = 0; i < letterCount; i++) {
  letterNumbers[String.fromCharCode(97 + i)] = i
  letterNumbers[String.fromCharCode(97 + i).toUpperCase()] = i
}

export function getLetterNumber(letter) {
  return letterNumbers[letter]
}

export function getAvatarColor(letter) {
  if (!letter || letter.trim().lenght <= 0) return 0
  const number = getLetterNumber(letter)
  return number % 9
}
