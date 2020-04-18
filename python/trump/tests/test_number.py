import unittest

from trump.number import Number


class TestNumber(unittest.TestCase):
    def test_construct(self):
        numbers = [0, 14]
        for number in numbers:
            with self.subTest(number=number):
                with self.assertRaises(ValueError):
                    Number(number)

        numbers = [1, 13]
        for number in numbers:
            with self.subTest(number=number):
                n = Number(number)
                self.assertEqual(number, n.value)

    def test_all(self):
        all = Number.all()
        numbers = range(1, 13 + 1)
        for target in all:
            self.assertIn(target.value, numbers)
