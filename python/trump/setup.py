import setuptools

with open("README.md", "r") as fh:
    long_description = fh.read()

setuptools.setup(
    name="Trump",  # Replace with your own username
    version="0.0.1",
    author="mitsuru793",
    author_email="mitsuru793@gmail.com",
    description="Domain models for trump.",
    long_description=long_description,
    long_description_content_type="text/markdown",
    url="https://github.com/mitsuru793/example-trump/python",
    packages=setuptools.find_packages(),
    classifiers=[
        "Programming Language :: Python :: 3",
        "License :: OSI Approved :: MIT License",
        "Operating System :: OS Independent",
    ],
    python_requires='>=3.6',
)
