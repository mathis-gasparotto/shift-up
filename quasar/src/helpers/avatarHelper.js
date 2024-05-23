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
  const number = getLetterNumber(letter)
  return number % 9
}
