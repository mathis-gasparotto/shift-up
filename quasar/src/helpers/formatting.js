export function strMaxLenght(str, max) {
  return str.length > max ? str.slice(0, max) + '...' : str
}
