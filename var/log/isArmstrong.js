/**
 * Checks if a number is an ArmStrong number
 * 
 * @param {number} number
 * 
 * @return {boolean} Returns TRUE if the number is Armstrong, otherwise FALSE.
 */
function isArmStrong(number) {
  let is3Digit = number.toString().length === 3;
  if (!is3Digit) {
    console.log(`${number} is not a 3-digit number`);
    return false;
  }

  let [first, second, third] = number.toString().split("").map(e => parseInt(e));
  let sumOfCubes = [first, second, third].reduce((a, e, _i) => {
    console.log(`Step ${_i}`)
    console.log(`a (prev value) is ${a} `);

    console.log("Now the current value is: ");
    console.log(a + (e ** 3));

    console.log("\n");

    return a + (e ** 3);
  });

  let isArmStrong = sumOfCubes === number;

  if (isArmStrong) {
    console.log(`${number} is an Armstrong number`);
  } else {
    console.log(`${number} is not an Armstrong number`);
  }

  return isArmStrong;
}


isArmStrong(153);